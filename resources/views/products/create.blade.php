@extends('layouts.mobile')

@section('title', 'Add Product')
@section('header-title', 'Add Product')

@section('content')
<div class="bg-white rounded-lg shadow p-5 mb-5">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf
        
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="space-y-5">
            <!-- Section: Basic Information -->
            <div class="pb-4 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Product Information</h3>
                
                <!-- Product name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        class="w-full h-12 px-4 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-base"
                        placeholder="Enter product name"
                        required
                    >
                </div>
                
                <!-- Category -->
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select 
                            name="category_id" 
                            id="category_id" 
                            class="w-full h-12 px-4 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 appearance-none text-base"
                            required
                        >
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="4" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-base px-2 py-2"
                        placeholder="Describe your product (optional)"
                    >{{ old('description') }}</textarea>
                </div>
            </div>
            
            <!-- Section: Pricing & Stock -->
            <div class="pb-4 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Pricing & Stock</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-base">Rp</span>
                            </div>
                            <input 
                                type="number" 
                                name="price" 
                                id="price" 
                                value="{{ old('price') }}" 
                                min="0" 
                                step="1000"
                                class="w-full h-12 pl-10 px-4 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-base"
                                placeholder="0"
                                required
                            >
                        </div>
                    </div>
                    
                    <!-- Initial Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Initial Stock <span class="text-red-500">*</span></label>
                        <input 
                            type="number" 
                            name="stock" 
                            id="stock" 
                            value="{{ old('stock', 0) }}" 
                            min="0" 
                            class="w-full h-12 px-4 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-base"
                            placeholder="0"
                            required
                        >
                    </div>
                </div>
            </div>
            
            <!-- Section: Product Image -->
            <div class="pb-4 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Product Image</h3>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <!-- Image upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Upload Image <span class="text-red-500">*</span></label>
                        
                        <!-- Image preview area -->
                        <div id="imagePreview" class="hidden mb-4">
                            <div class="relative w-full h-44 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                <img id="previewImg" src="#" alt="Preview" class="h-full w-full object-contain">
                                <button type="button" id="removeImage" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Upload button area -->
                        <div id="uploadArea" class="mb-2">
                            <label for="image" class="cursor-pointer flex flex-col items-center justify-center w-full h-44 border-2 border-gray-300 border-dashed rounded-lg bg-white hover:bg-gray-50 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                                </div>
                                <input 
                                    type="file" 
                                    name="image" 
                                    id="image" 
                                    class="hidden"
                                    accept="image/*"
                                    required
                                >
                            </label>
                        </div>
                        
                        <div id="imageError" class="text-red-600 text-sm mt-2 hidden">
                            Please select an image file
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section: Product Status -->
            <div class="pb-2">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Product Status</h3>
                
                <!-- Active status -->
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active" 
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                        value="1" 
                        checked
                    >
                    <label for="is_active" class="ml-3 block text-base text-gray-700">
                        Product is active and available for sale
                    </label>
                </div>
            </div>
        </div>
        
        <div class="mt-8 flex items-center justify-end space-x-3">
            <a href="{{ route('products.index') }}" class="flex-1 sm:flex-none text-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">
                Cancel
            </a>
            <button 
                type="submit" 
                id="saveButton"
                class="flex-1 sm:flex-none flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Product
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Improved image handling script with better UX
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeButton = document.getElementById('removeImage');
        const imageError = document.getElementById('imageError');
        const form = document.getElementById('productForm');
        const saveButton = document.getElementById('saveButton');
        const uploadArea = document.getElementById('uploadArea');
        
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
                    uploadArea.classList.remove('hidden');
                    return;
                }
                
                // Validate file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    imageError.textContent = 'Image must be less than 2MB';
                    imageError.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                    uploadArea.classList.remove('hidden');
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                };
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
                uploadArea.classList.remove('hidden');
            }
        });
        
        // Remove selected image
        removeButton.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            imageError.classList.add('hidden');
        });
        
        // Drag and drop functionality
        const dropArea = document.querySelector('label[for="image"]');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropArea.classList.add('border-blue-400', 'bg-blue-50');
        }
        
        function unhighlight() {
            dropArea.classList.remove('border-blue-400', 'bg-blue-50');
        }
        
        dropArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                imageInput.files = files;
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                imageInput.dispatchEvent(event);
            }
        }
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            if (!imageInput.files || !imageInput.files[0]) {
                e.preventDefault();
                imageError.textContent = 'Please select an image file';
                imageError.classList.remove('hidden');
                uploadArea.scrollIntoView({ behavior: 'smooth' });
            }
        });
        
        // Prevent double submission with visual feedback
        saveButton.addEventListener('click', function() {
            if (form.checkValidity() && imageInput.files && imageInput.files[0]) {
                this.disabled = true;
                
                // Change button appearance to show it's processing
                this.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
                form.submit();
            }
        });
    });
</script>
@endpush