<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\StockMovement;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::all();
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
                   ->get();
                   
        return view('stock.movements', compact('movements'));
    }
}
