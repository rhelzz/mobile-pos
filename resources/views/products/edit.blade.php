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
            
            <!-- Image upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                <div class="mt-1 flex items-center">
                    <label for="image" class="w-full flex flex-col items-center px-4 py-6 bg-white rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-50">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-32 object-contain">
                            <p class="mt-2 text-xs text-blue-500">Click to change</p>
                        @else
                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-1 text-sm text-gray-500">Click to upload image</p>
                        @endif
                        <input id="image" name="image" type="file" class="hidden" accept="image/*">
                    </label>
                </div>
                <p class="mt-2 text-xs text-gray-500">Recommended: 600x600px JPG or PNG</p>
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
            <!-- Delete product button -->
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-red-500 text-red-500 rounded-lg text-sm font-medium hover:bg-red-500 hover:text-white transition-colors">
                    Delete
                </button>
            </form>
            
            <div>
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500 mr-2">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Update Product
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Preview uploaded image
    const imageInput = document.getElementById('image');
    const imageLabel = imageInput.parentElement;
    
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Remove existing content
                while (imageLabel.firstChild) {
                    imageLabel.removeChild(imageLabel.firstChild);
                }
                
                // Create preview image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('h-32', 'object-contain');
                
                // Add "Change" text
                const changeText = document.createElement('p');
                changeText.textContent = 'Click to change';
                changeText.classList.add('mt-2', 'text-xs', 'text-blue-500');
                
                // Append to label
                imageLabel.appendChild(img);
                imageLabel.appendChild(changeText);
            };
            
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush