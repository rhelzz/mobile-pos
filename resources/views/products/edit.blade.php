@extends('layouts.mobile')

@section('title', 'Edit Product')
@section('header-title', 'Edit Product')

@section('content')
<div class="bg-white rounded-lg shadow-md p-5 mb-5">
    @if ($errors->any())
        <div class="mb-5 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200">
            <h3 class="font-medium mb-1">Please check the following errors:</h3>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Product Form Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">Product Details</h2>
        <p class="text-gray-500">Update the product information below</p>
    </div>
    
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Product name -->
            <div>
                <label for="name" class="block text-base font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $product->name) }}" 
                    class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                    placeholder="Enter product name"
                >
            </div>
            
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-base font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                <select 
                    name="category_id" 
                    id="category_id" 
                    class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
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
            
            <!-- Price and Stock in 2 columns -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Price -->
                <div>
                    <label for="price" class="block text-base font-medium text-gray-700 mb-2">Price (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">Rp</span>
                        </div>
                        <input 
                            type="number" 
                            name="price" 
                            id="price" 
                            value="{{ old('price', $product->price) }}" 
                            min="0" 
                            step="1000"
                            class="w-full pl-10 pr-3 py-3 rounded-lg border-gray-300 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required
                            placeholder="0"
                        >
                    </div>
                </div>
                
                <!-- Current Stock -->
                <div>
                    <label for="stock" class="block text-base font-medium text-gray-700 mb-2">Current Stock <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        value="{{ old('stock', $product->stock) }}" 
                        min="0" 
                        class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required
                        placeholder="0"
                    >
                </div>
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-base font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                    placeholder="Enter product description (optional)"
                >{{ old('description', $product->description) }}</textarea>
            </div>
            
            <!-- Image upload with better styling -->
            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                <label class="block text-base font-medium text-gray-700 mb-3">Product Image</label>
                
                <!-- Current image preview -->
                <div class="mb-4">
                    <p class="font-medium text-gray-700 mb-2">Current Image:</p>
                    <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                        <img 
                            src="{{ asset('storage/' . $product->image_path) }}" 
                            alt="{{ $product->name }}" 
                            class="h-48 mx-auto object-contain"
                            onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';"
                        >
                    </div>
                </div>
                
                <!-- New image upload -->
                <div>
                    <p class="font-medium text-gray-700 mb-2">Change Image:</p>
                    <label 
                        for="image" 
                        class="block w-full border-2 border-dashed border-gray-300 rounded-lg py-4 px-3 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="mt-2 block text-base font-medium text-blue-600">
                            Click to upload new image
                        </span>
                        <span class="mt-1 block text-sm text-gray-500">
                            JPG, PNG or GIF (Max. 2MB)
                        </span>
                        <input 
                            type="file" 
                            name="image" 
                            id="image" 
                            class="hidden"
                            accept="image/*"
                        >
                    </label>
                    <div id="file-selected" class="mt-2 text-sm text-gray-500 text-center">No new file selected</div>
                    <p class="mt-2 text-xs text-gray-500 text-center">Leave empty to keep current image</p>
                </div>
            </div>
            
            <!-- Active status with better styling -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="flex h-6 items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            id="is_active" 
                            class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                            value="1" 
                            {{ $product->is_active ? 'checked' : '' }}
                        >
                    </div>
                    <div class="ml-3">
                        <label for="is_active" class="block font-medium text-gray-700">
                            Product is active
                        </label>
                        <p class="text-sm text-gray-500">
                            This product will be visible and available for purchase
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-8 pt-5 border-t border-gray-200">
            <div class="flex items-center justify-end space-x-3">
                <a 
                    href="{{ route('products.index') }}" 
                    class="px-5 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
                >
                    Update Product
                </button>
            </div>
        </div>
    </form>
    
    <!-- Danger Zone -->
    <div class="mt-10 border-t-2 border-red-100 pt-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-5">
            <h3 class="text-lg font-medium text-red-800 mb-1">Danger Zone</h3>
            <p class="text-sm text-red-600 mb-4">Once deleted, this product cannot be recovered</p>
            
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-3 border border-transparent font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Show filename when selected
    document.getElementById('image').addEventListener('change', function() {
        const fileSelectedDiv = document.getElementById('file-selected');
        if (this.files && this.files[0]) {
            fileSelectedDiv.innerHTML = '<span class="font-medium text-blue-600">' + this.files[0].name + '</span> selected';
        } else {
            fileSelectedDiv.textContent = 'No new file selected';
        }
    });
</script>
@endpush