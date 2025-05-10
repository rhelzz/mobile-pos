<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')
                      ->orderBy('created_at', 'desc')
                      ->get();
                      
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
            'discount_amount' => 'nullable|numeric|min:0',
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
            
            // Apply discount if provided
            $discountAmount = $request->discount_amount ?? 0;
            
            // Calculate final amount
            $finalAmount = $totalAmount + $taxAmount - $discountAmount;
            
            // Create transaction
            $transaction = Transaction::create([
                'invoice_number' => 'INV-' . date('Ymd') . '-' . Str::random(5),
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
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
