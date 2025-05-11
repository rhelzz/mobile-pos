@extends('layouts.mobile')

@section('title', 'Add Product')
@section('header-title', 'Add Product')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 mb-4">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        
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
                    value="{{ old('name') }}" 
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
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        value="{{ old('price') }}" 
                        min="0" 
                        step="1000"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required
                    >
                </div>
                
                <!-- Initial Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Initial Stock *</label>
                    <input 
                        type="number" 
                        name="stock" 
                        id="stock" 
                        value="{{ old('stock', 0) }}" 
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
                >{{ old('description') }}</textarea>
            </div>
            
            <!-- Image upload - Improved to ensure reliability -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Product Image *</label>
                <div class="mt-1">
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
                        required
                    >
                </div>
                <div id="imagePreview" class="mt-2 hidden">
                    <img id="previewImg" src="#" alt="Preview" class="h-32 object-contain border border-gray-200 rounded p-2">
                    <button type="button" id="removeImage" class="mt-2 text-xs text-red-500 hover:text-red-700">
                        Remove image
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-500">Required: JPG, PNG or GIF (Max. 2MB)</p>
                <div id="imageError" class="text-red-600 text-xs mt-1 hidden">
                    Please select an image file
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
                    checked
                >
                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                    Product is active
                </label>
            </div>
        </div>
        
        <div class="mt-6 flex items-center justify-end">
            <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500 mr-2">
                Cancel
            </a>
            <button 
                type="submit" 
                id="saveButton"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"
            >
                Save Product
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Improved image handling script
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeButton = document.getElementById('removeImage');
        const imageError = document.getElementById('imageError');
        const form = document.getElementById('productForm');
        const saveButton = document.getElementById('saveButton');
        
        // Preview image when selected
        imageInput.addEventListener('change', function() {
            // Reset error message
            imageError.classList.add('hidden');
            
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file is an image
                if (!file.type.match('image.*')) {
                    imageError.textContent = 'Please select an image file (JPEG, PNG, GIF)';
                    imageError.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    imageError.textContent = 'Image must be less than 2MB';
                    imageError.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        });
        
        // Remove selected image
        removeButton.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.classList.add('hidden');
            imageInput.focus();
        });
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            if (!imageInput.files || !imageInput.files[0]) {
                e.preventDefault();
                imageError.textContent = 'Please select an image file';
                imageError.classList.remove('hidden');
                imageInput.focus();
            }
        });
        
        // Prevent double submission
        saveButton.addEventListener('click', function() {
            if (form.checkValidity()) {
                this.disabled = true;
                this.textContent = 'Saving...';
                form.submit();
            }
        });
    });
</script>
@endpush