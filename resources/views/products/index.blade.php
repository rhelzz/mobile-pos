@extends('layouts.mobile')

@section('title', 'Products')
@section('header-title', 'Products')

@section('subheader')
<div class="w-full flex justify-between items-center">
    <input type="text" id="search" placeholder="Search products..." class="w-full px-3 py-1 text-sm rounded-lg border-0 focus:ring-0 bg-blue-800 bg-opacity-30 text-white placeholder-blue-200">
    <a href="{{ route('products.create') }}" class="ml-2 bg-white text-blue-600 p-1 rounded-full flex items-center justify-center shadow-sm hover:bg-blue-50 transition-all">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>
@endsection

@section('content')
<div class="mb-4">
    <div class="flex items-center mb-2">
        <h2 class="text-lg font-medium">All Products</h2>
        <div class="ml-auto text-sm flex items-center">
            <span class="text-gray-500">{{ count($products) }} products</span>
        </div>
    </div>
    
    <div x-data="{ activeCategory: 'all' }">
        <!-- Category filters - horizontal scrollable -->
        <div class="-mx-4 px-4 mb-3 overflow-x-auto">
            <div class="flex items-center space-x-2 whitespace-nowrap pb-1">
                <button 
                    @click="activeCategory = 'all'" 
                    :class="activeCategory === 'all' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700'"
                    class="px-3 py-1 rounded-full text-sm font-medium transition-all">
                    All
                </button>
                
                @foreach($categories ?? [] as $category)
                    <button 
                        @click="activeCategory = '{{ $category->id }}'" 
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700'"
                        class="px-3 py-1 rounded-full text-sm font-medium transition-all">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Products list -->
        <div class="space-y-3">
            @forelse($products as $product)
                <div 
                    x-show="activeCategory === 'all' || activeCategory === '{{ $product->category_id }}'"
                    class="product-item bg-white rounded-lg shadow overflow-hidden flex hover:shadow-md transition-shadow"
                    data-name="{{ strtolower($product->name) }}"
                >
                    <!-- Product image -->
                    <div class="w-20 h-20 bg-gray-100 flex-shrink-0">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Product details -->
                    <div class="p-3 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-medium text-gray-800">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="font-semibold text-blue-600">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-sm {{ $product->stock <= 5 ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                Stock: {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Actions button -->
                    <div class="border-l border-gray-100 flex items-stretch">
                        <a href="{{ route('products.edit', $product) }}" class="px-3 flex items-center text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-lg shadow text-center">
                    <div class="mb-4 text-gray-400">
                        <svg class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 mb-4">No products found</p>
                    <a href="{{ route('products.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition-all">
                        Add First Product
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Product search functionality
    const searchInput = document.getElementById('search');
    const productItems = document.querySelectorAll('.product-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let foundCount = 0;
        
        productItems.forEach(item => {
            const productName = item.dataset.name;
            
            if (productName.includes(searchTerm)) {
                item.style.display = 'flex';
                foundCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show "no results" message if needed
        const noResults = document.getElementById('noResults');
        if (noResults) {
            if (searchTerm && foundCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }
    });
</script>
@endpush