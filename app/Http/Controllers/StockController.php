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

    public function movements(Request $request)
    {
        // Memulai query dasar
        $query = StockMovement::with('product', 'transaction');
        
        // Filter berdasarkan tanggal mulai
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        // Filter berdasarkan tanggal akhir
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Filter berdasarkan tipe pergerakan (in/out)
        if ($request->has('movement_type') && $request->movement_type) {
            $query->where('type', $request->movement_type);
        }
        
        // Filter berdasarkan produk
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        // Mendapatkan data dengan pagination
        $movements = $query->orderBy('created_at', 'desc')
                   ->paginate(6);
        
        // Mengambil semua produk untuk dropdown filter
        $products = Product::orderBy('name')->get();
        
        // Mendapatkan nama produk terpilih untuk ditampilkan di filter
        $selectedProduct = null;
        if ($request->product_id) {
            $selectedProduct = Product::find($request->product_id)?->name;
        }
        
        return view('stock.movements', compact('movements', 'products', 'selectedProduct'));
    }

    public function truncateMovements()
    {
        // Langsung menghapus semua data pergerakan stok tanpa pengecekan izin
        StockMovement::truncate();
        
        return redirect()->route('stock.movements')
            ->with('success', 'Semua data pergerakan stok berhasil dihapus.');
    }
}
