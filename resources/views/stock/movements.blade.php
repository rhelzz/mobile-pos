@extends('layouts.mobile')

@section('title', 'Stock Movements')
@section('header-title', 'Stock Movements')

@section('content')
<div class="mb-4">
    <div class="flex items-center mb-4">
        <h2 class="text-lg font-medium">Stock Movement History</h2>
        <a href="{{ route('stock.index') }}" class="ml-auto text-sm text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Stock
        </a>
    </div>
    
    <!-- Improved Filter Section: Collapsible with toggle button -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4" x-data="{ showFilters: false }">
        <!-- Filter header with toggle -->
        <div class="p-4 flex items-center justify-between border-b border-gray-100 cursor-pointer" @click="showFilters = !showFilters">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-sm font-medium text-gray-700">Filter Movements</h3>
            </div>
            <div class="text-gray-500" x-text="showFilters ? '−' : '+'"></div>
        </div>
        
        <!-- Filter content -->
        <div class="p-4" x-show="showFilters" x-cloak>
            <form action="{{ route('stock.movements') }}" method="GET" id="filter-form">
                <div class="space-y-3">
                    <!-- Date filters -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="start_date" class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                            <input 
                                type="date" 
                                name="start_date" 
                                id="start_date" 
                                value="{{ request('start_date') }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                            >
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                            <input 
                                type="date" 
                                name="end_date" 
                                id="end_date" 
                                value="{{ request('end_date') }}"
                                class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                            >
                        </div>
                    </div>
                    
                    <!-- Type and Product filters -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="movement_type" class="block text-xs font-medium text-gray-500 mb-1">Movement Type</label>
                            <div class="relative">
                                <select 
                                    name="movement_type" 
                                    id="movement_type"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm appearance-none"
                                >
                                    <option value="">All Types</option>
                                    <option value="in" {{ request('movement_type') == 'in' ? 'selected' : '' }}>Stock In</option>
                                    <option value="out" {{ request('movement_type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="product_id" class="block text-xs font-medium text-gray-500 mb-1">Product</label>
                            <div class="relative">
                                <select 
                                    name="product_id" 
                                    id="product_id"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm appearance-none"
                                >
                                    <option value="">All Products</option>
                                    @foreach($products ?? [] as $product)
                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action buttons -->
                    <div class="flex justify-between pt-2">
                        <a 
                            href="{{ route('stock.movements') }}" 
                            class="px-3 py-2 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200"
                        >
                            Clear Filters
                        </a>
                        
                        <button 
                            type="submit" 
                            class="px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                        >
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Results Title with Active Filters Display -->
    @if(request('start_date') || request('end_date') || request('movement_type') || request('product_id'))
    <div class="bg-blue-50 text-blue-700 rounded-lg p-3 mb-4 flex items-start justify-between">
        <div>
            <h3 class="text-sm font-medium">Active Filters:</h3>
            <div class="mt-1 flex flex-wrap gap-2">
                @if(request('start_date') || request('end_date'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white text-blue-600">
                        Date: {{ request('start_date', 'Any') }} to {{ request('end_date', 'Any') }}
                    </span>
                @endif
                
                @if(request('movement_type'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white text-blue-600">
                        Type: {{ request('movement_type') == 'in' ? 'Stock In' : 'Stock Out' }}
                    </span>
                @endif
                
                @if(request('product_id'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white text-blue-600">
                        Product: {{ $selectedProduct ?? request('product_id') }}
                    </span>
                @endif
            </div>
        </div>
        <a href="{{ route('stock.movements') }}" class="text-xs text-blue-700 hover:text-blue-900">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>
    </div>
    @endif
    
    <!-- Actions menu -->
    <div class="flex justify-end mb-3">
        <!-- Tombol truncate dibuat selalu visible tanpa kondisional -->
        <button 
            type="button"
            onclick="confirmTruncate()"
            class="px-3 py-1.5 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition-colors duration-200 shadow-sm flex items-center"
        >
            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            Clear All Records
        </button>
    </div>
    
    <!-- Movements List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
        <div class="divide-y divide-gray-100">
            @forelse($movements as $movement)
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-medium">{{ $movement->product->name }}</h4>
                            <p class="text-xs text-gray-500">
                                {{ $movement->created_at->format('d M Y - H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center">
                            @if($movement->type === 'in')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    +{{ $movement->quantity }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    -{{ $movement->quantity }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-1 text-sm">
                        @if($movement->transaction)
                            <span class="text-gray-600">
                                Sale: {{ $movement->transaction->invoice_number }}
                            </span>
                        @else
                            <span class="text-gray-600">
                                {{ $movement->reference ?? 'Stock Update' }}
                            </span>
                        @endif
                        
                        @if($movement->notes)
                            <p class="text-xs text-gray-500 mt-1">{{ $movement->notes }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <p class="mt-2">No stock movements found</p>
                    @if(request('start_date') || request('end_date') || request('movement_type') || request('product_id'))
                        <a href="{{ route('stock.movements') }}" class="text-sm text-blue-600 block mt-1">Clear filters</a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Pagination Navigation -->
    <div class="mt-4 flex justify-center">
        {{ $movements->appends(request()->query())->links('pagination.simple-tailwind') }}
    </div>
</div>

<!-- Truncate Confirmation Modal -->
<div 
    id="truncate-modal" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
>
    <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
        <h3 class="text-lg font-bold text-red-600 mb-3">Clear All Stock Movements?</h3>
        <p class="text-gray-700 mb-4">
            This will permanently delete ALL stock movement records. This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-3">
            <button 
                onclick="closeModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
            >
                Cancel
            </button>
            <form action="{{ route('stock.truncate') }}" method="POST">
                @csrf
                @method('DELETE')
                <button 
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                >
                    Yes, Clear All
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmTruncate() {
        document.getElementById('truncate-modal').classList.remove('hidden');
    }
    
    function closeModal() {
        document.getElementById('truncate-modal').classList.add('hidden');
    }
    
    // Close modal on background click
    document.getElementById('truncate-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection