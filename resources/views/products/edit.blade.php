@extends('layouts.mobile')

@section('title', 'Edit Product')
@section('header-title', 'Edit Product')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 mb-4">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-lg border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="space-y-4">
            <!-- Product name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $product->name) }}" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
            </div>
            
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select 
                    name="category_id" 
                    id="category_id" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rp) *</label>
                    <input 
                        type="number" 
                        name="price" 
                        id="price" 
                        value="{{ old('price', $product->price) }}" 
                        min="0" 
                        step="1000"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required
                    >
                </div>
                
                <!-- Current Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Current Stock *</label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        value="{{ old('stock', $product->stock) }}" 
                        min="0" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required
                    >
                </div>
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                >{{ old('description', $product->description) }}</textarea>
            </div>
            
            <!-- Image upload - Simplified -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Product Image</label>
                
                <!-- Current image preview -->
                <div class="mb-3">
                    <div class="border border-gray-200 rounded p-2 bg-gray-50">
                        <img 
                            src="{{ asset('storage/' . $product->image_path) }}" 
                            alt="{{ $product->name }}" 
                            class="h-40 mx-auto object-contain"
                            onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';"
                        >
                    </div>
                </div>
                
                <!-- New image upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Change image:</label>
                    <input 
                        type="file" 
                        name="image" 
                        id="image" 
                        class="w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-medium
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100"
                        accept="image/*"
                    >
                    <p class="mt-1 text-xs text-gray-500">Leave empty to keep current image. JPG, PNG or GIF (Max. 2MB)</p>
                </div>
            </div>
            
            <!-- Active status -->
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    name="is_active" 
                    id="is_active" 
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                    value="1" 
                    {{ $product->is_active ? 'checked' : '' }}
                >
                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                    Product is active
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex items-center justify-between">
            <!-- Delete button telah dipisahkan, bukan dalam form update -->
            <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                Cancel
            </a>
            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"
            >
                Update Product
            </button>
        </div>
    </form>
    
    <!-- Form delete HARUS terpisah dari form update -->
    <div class="border-t border-gray-200 mt-6 pt-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm font-medium text-gray-700">Danger Zone</h3>
                <p class="text-xs text-gray-500">Once deleted, this product cannot be recovered</p>
            </div>
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-red-500 text-red-500 rounded-lg text-sm font-medium hover:bg-red-500 hover:text-white transition-colors">
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</div>
@endsection