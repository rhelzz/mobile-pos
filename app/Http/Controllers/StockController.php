<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\StockMovement;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        
        $query = Product::with('category');
        
        // Filter berdasarkan stok
        switch ($filter) {
            case 'low':
                // Produk dengan stok kurang dari atau sama dengan 5 (low stock)
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
                break;
                
            case 'out':
                // Produk dengan stok 0 (out of stock)
                $query->where('stock', 0);
                break;
                
            default:
                // Semua produk (default)
                break;
        }
        
        $products = $query->get();
        
        return view('stock.index', compact('products'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Product::findOrFail($request->product_id);
        
        // Update product stock
        $product->stock += $request->quantity;
        $product->save();
        
        // Record stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'type' => 'in',
            'reference' => 'Stock Addition',
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('stock.index')
            ->with('success', 'Stock updated successfully.');
    }

    public function movements()
    {
        $movements = StockMovement::with('product', 'transaction')
                   ->orderBy('created_at', 'desc')
                   ->paginate(6); // Ubah get() menjadi paginate(6)
                   
        return view('stock.movements', compact('movements'));
    }
}
