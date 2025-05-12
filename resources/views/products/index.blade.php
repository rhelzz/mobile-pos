@extends('layouts.mobile')

@section('title', 'Products')
@section('header-title', 'Products')

@section('subheader')
<div class="w-full flex justify-between items-center">
    <div class="w-full relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-blue-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input 
            type="text" 
            id="search" 
            placeholder="Search products..." 
            class="w-full pl-10 pr-3 py-2 text-sm rounded-lg border-0 focus:ring-1 focus:ring-blue-400 bg-blue-800 bg-opacity-30 text-white placeholder-blue-200"
        >
    </div>
    <a href="{{ route('products.create') }}" class="ml-2 bg-white text-blue-600 p-2 rounded-full flex items-center justify-center shadow-sm hover:bg-blue-50 hover:scale-105 transition-all">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>
@endsection

@section('content')
<div class="mb-5">
    <!-- Header Section -->
    <div class="flex items-center mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Products</h2>
            <p class="text-gray-500 text-sm">Manage your inventory</p>
        </div>
        <div class="ml-auto">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ count($products) }} {{ Str::plural('product', count($products)) }}
            </span>
        </div>
    </div>
    
    <div x-data="{ activeCategory: 'all' }">
        <!-- Category filters - horizontal scrollable with indicator -->
        <div class="-mx-4 px-4 mb-4 overflow-x-auto">
            <div class="flex items-center space-x-2 whitespace-nowrap pb-2">
                <button 
                    @click="activeCategory = 'all'" 
                    :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all">
                    All Items
                </button>
                
                @foreach($categories ?? [] as $category)
                    <button 
                        @click="activeCategory = '{{ $category->id }}'" 
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- No results message (hidden by default) -->
        <div id="noResults" class="hidden bg-yellow-50 border border-yellow-100 rounded-lg p-4 mb-4 text-center">
            <p class="text-yellow-700">No products match your search. Try a different keyword.</p>
        </div>

        <!-- Products list -->
        <div class="space-y-3 mb-2">
            @forelse($products as $product)
                <div 
                    x-show="activeCategory === 'all' || activeCategory === '{{ $product->category_id }}'"
                    x-transition
                    class="product-item bg-white rounded-xl shadow-sm overflow-hidden flex hover:shadow-md transition-all duration-200"
                    data-name="{{ strtolower($product->name) }}"
                    data-category="{{ strtolower($product->category->name ?? '') }}"
                >
                    <!-- Product image - with aspect ratio lock -->
                    <div class="w-24 h-24 bg-gray-100 flex-shrink-0 relative overflow-hidden">
                        @if($product->image_path)
                            <img 
                                src="{{ asset('storage/' . $product->image_path) }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover"
                                onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.parentElement.classList.add('bg-gray-50');"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-400">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Stock indicator overlay for low stock items -->
                        @if($product->stock <= 5)
                            <div class="absolute top-1 right-1">
                                <span class="flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product details -->
                    <div class="p-3 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-medium text-gray-800 leading-tight">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 flex items-center mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="font-semibold text-blue-600">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-xs px-2 py-1 rounded-full {{ $product->stock <= 5 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                                Stock: {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Actions button -->
                    <div class="border-l border-gray-100 flex items-stretch">
                        <a href="{{ route('products.edit', $product) }}" class="px-3 flex items-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <!-- Empty state with better visual -->
                <div class="bg-white p-8 rounded-xl shadow-sm text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-1">No Products Found</h3>
                    <p class="text-gray-500 mb-4">Start by adding your first product</p>
                    <a href="{{ route('products.create') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-sm hover:bg-blue-700 transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add First Product
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Quick Bottom Action Bar (when products exist) -->
        @if(count($products) > 0)
            <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100 flex justify-between items-center">
                <div class="text-sm text-blue-800">
                    <span class="font-medium">Tip:</span> Tap a product card to view details
                </div>
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Product
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Enhanced Product search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const productItems = document.querySelectorAll('.product-item');
        const noResults = document.getElementById('noResults');
        
        // Save original display values
        productItems.forEach(item => {
            item.dataset.originalDisplay = window.getComputedStyle(item).display;
        });
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let foundCount = 0;
            
            productItems.forEach(item => {
                const productName = item.dataset.name;
                const productCategory = item.dataset.category;
                
                // Search in name and category
                if (productName.includes(searchTerm) || productCategory.includes(searchTerm)) {
                    item.style.display = item.dataset.originalDisplay;
                    foundCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show "no results" message if needed
            if (noResults) {
                if (searchTerm && foundCount === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        });
        
        // Make entire product card clickable except edit button
        productItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Don't trigger if clicked on the edit button
                if (!e.target.closest('a[href*="products/"]')) {
                    // Extract product ID from edit link
                    const editLink = this.querySelector('a[href*="products/"]');
                    if (editLink) {
                        window.location.href = editLink.href;
                    }
                }
            });
            
            // Add cursor pointer style
            item.classList.add('cursor-pointer');
        });
    });
</script>
@endpush