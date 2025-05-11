<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048', // Required image upload (max 2MB)
            'is_active' => 'boolean',
        ]);

        // Handle the image upload
        $validated['image_path'] = $request->file('image')->store('products', 'public');
        
        // Set is_active value
        $validated['is_active'] = $request->has('is_active');

        // Create new product
        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => $product->image_path ? 'nullable|image|max:2048' : 'required|image|max:2048', // Required only if no existing image
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image
            Storage::disk('public')->delete($product->image_path);
            
            // Store the new image
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        
        // Set is_active value
        $validated['is_active'] = $request->has('is_active');

        // Update the product
        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product is used in any transaction
        $isUsed = $product->transactionItems()->exists();
        
        if ($isUsed) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product as it is used in transactions.');
        }
        
        // Delete image
        Storage::disk('public')->delete($product->image_path);
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
