<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'transactionItems')
                ->orderBy('created_at', 'desc');
        
        // Apply date filters if provided
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        // Apply payment method filter if provided
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
                      
        $transactions = $query->paginate(5)->withQueryString();
        
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
                  ->where('stock', '>', 0)
                  ->get();
                  
        return view('cashier', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer,other',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'customer_name' => 'nullable|string|max:255', // Tambahkan validasi untuk customer_name
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            
            $totalAmount = 0;
            $items = [];
            
            // Calculate total and prepare items
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);
                
                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                
                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;
                
                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
                
                // Update product stock
                $product->stock -= $item['quantity'];
                $product->save();
            }
            
            // Calculate tax if provided
            $taxAmount = 0;
            if ($request->filled('tax_percent')) {
                $taxAmount = ($totalAmount * $request->tax_percent) / 100;
            }
            
            // Apply discount percent if provided
            $discountAmount = 0;
            if ($request->filled('discount_percent')) {
                $discountAmount = ($totalAmount * $request->discount_percent) / 100;
            }
            
            // Calculate final amount
            $finalAmount = $totalAmount + $taxAmount - $discountAmount;
            
            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . Str::random(5),
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name, // Simpan customer_name
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'discount_percent' => $request->discount_percent ?? 0,
                'final_amount' => $finalAmount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);
            
            // Create transaction items
            foreach ($items as $item) {
                $transactionItem = new TransactionItem($item);
                $transaction->transactionItems()->save($transactionItem);
                
                // Record stock movement
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => 'out',
                    'transaction_id' => $transaction->id,
                    'reference' => 'Sale: ' . $transaction->invoice_number,
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaction completed successfully',
                'transaction' => $transaction,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('transactionItems.product', 'user');
        return view('transactions.show', compact('transaction'));
    }
}
