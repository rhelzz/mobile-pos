@extends('layouts.mobile')

@section('title', 'Stock Management')
@section('header-title', 'Stock Management')

@section('content')
<div x-data="{ showAddStock: false }">
    <!-- Button to toggle add stock form with improved styling -->
    <div class="mb-4">
        <button 
            @click="showAddStock = !showAddStock" 
            class="w-full py-3 px-5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl text-sm font-medium flex items-center justify-center shadow-md hover:shadow-lg transition duration-200"
        >
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Stock
        </button>
    </div>
    
    <!-- Improved add stock form with slide-in animation -->
    <div 
        x-show="showAddStock" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="bg-white rounded-xl shadow-lg p-5 mb-5 border border-blue-100" 
        x-cloak
    >
        <h3 class="font-semibold text-lg mb-4 text-gray-800 border-b pb-2">Add New Stock</h3>
        
        <form action="{{ route('stock.add') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-lg border border-red-200 animate-pulse">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="space-y-4">
                <!-- Product select with improved styling -->
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                    <div class="relative">
                        <select 
                            name="product_id" 
                            id="product_id" 
                            class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-blue-500 focus:ring focus:ring-blue-200 pr-10 transition-all duration-200 px-2 py-2"
                            required
                        >
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (Current stock: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Quantity with improved styling -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Add *</label>
                    <div class="flex rounded-lg shadow-sm">
                        <button type="button" 
                            onclick="decrementQty()"
                            class="px-3 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>
                        <input 
                            type="number" 
                            name="quantity" 
                            id="quantity" 
                            value="{{ old('quantity', 1) }}" 
                            min="1" 
                            class="flex-1 text-center border-y border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            required
                        >
                        <button type="button" 
                            onclick="incrementQty()"
                            class="px-3 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-r-lg hover:bg-gray-200 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Notes with improved styling -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        rows="3" 
                        placeholder="Optional notes about this stock addition"
                        class="w-full rounded-lg border-gray-300 bg-gray-50 px-2 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all duration-200"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <div class="mt-5 flex justify-end space-x-3">
                <button 
                    type="button" 
                    @click="showAddStock = false" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg text-sm font-medium hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md hover:shadow-lg transition-all duration-200"
                >
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Add Stock
                    </div>
                </button>
            </div>
        </form>
    </div>
    
    <!-- Quick filters with improved styling -->
    <div class="flex items-center space-x-2 mb-4 overflow-x-auto pb-2">
        <a href="{{ route('stock.index') }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-xs font-medium shadow-sm {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
            All Products
        </a>
        <a href="{{ route('stock.index', ['filter' => 'low']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-xs font-medium shadow-sm {{ request('filter') == 'low' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
            Low Stock
        </a>
        <a href="{{ route('stock.index', ['filter' => 'out']) }}" class="whitespace-nowrap px-4 py-2 rounded-lg text-xs font-medium shadow-sm {{ request('filter') == 'out' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-200' }}">
            Out of Stock
        </a>
    </div>
    
    <!-- Stock list with improved styling -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800">Current Stock</h3>
            <a href="{{ route('stock.movements') }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                <span>View Movements</span>
                <svg class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($products as $product)
                <div class="p-4 hover:bg-gray-50 transition-colors duration-150 flex justify-between items-center">
                    <div>
                        <h4 class="font-medium text-gray-800">{{ $product->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-lg {{ $product->stock <= 5 ? 'text-red-600' : ($product->stock <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $product->stock }}
                        </p>
                        <p class="text-xs text-gray-500">in stock</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <p class="mt-2">No products found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function incrementQty() {
        const input = document.getElementById('quantity');
        input.value = parseInt(input.value) + 1 || 1;
    }
    
    function decrementQty() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value) || 0;
        input.value = currentValue > 1 ? currentValue - 1 : 1;
    }
</script>
@endsection