@extends('layouts.mobile')

@section('title', 'Stock Management')
@section('header-title', 'Stock Management')

@section('content')
<div x-data="{ showAddStock: false }">
    <!-- Button to toggle add stock form -->
    <div class="mb-4">
        <button 
            @click="showAddStock = !showAddStock" 
            class="w-full py-2 px-4 bg-blue-600 text-white rounded-lg text-sm font-medium flex items-center justify-center"
        >
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Stock
        </button>
    </div>
    
    <!-- Add stock form -->
    <div x-show="showAddStock" class="bg-white rounded-lg shadow-sm p-4 mb-4" x-cloak>
        <h3 class="font-medium mb-3">Add Stock</h3>
        
        <form action="{{ route('stock.add') }}" method="POST">
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
            
            <div class="space-y-3">
                <!-- Product select -->
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-1">Product *</label>
                    <select 
                        name="product_id" 
                        id="product_id" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
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
                
                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Add *</label>
                    <input 
                        type="number" 
                        name="quantity" 
                        id="quantity" 
                        value="{{ old('quantity', 1) }}" 
                        min="1" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required
                    >
                </div>
                
                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        rows="2" 
                        placeholder="Optional notes about this stock addition"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end">
                <button type="button" @click="showAddStock = false" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500 mr-2">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Add Stock
                </button>
            </div>
        </form>
    </div>
    
    <!-- Quick filters -->
    <div class="flex items-center space-x-2 mb-4 overflow-x-auto pb-2">
        <a href="{{ route('stock.index') }}" class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-medium {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            All Products
        </a>
        <a href="{{ route('stock.index', ['filter' => 'low']) }}" class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-medium {{ request('filter') == 'low' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Low Stock
        </a>
        <a href="{{ route('stock.index', ['filter' => 'out']) }}" class="whitespace-nowrap px-3 py-1 rounded-full text-xs font-medium {{ request('filter') == 'out' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Out of Stock
        </a>
    </div>
    
    <!-- Stock list -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-medium">Current Stock</h3>
            <a href="{{ route('stock.movements') }}" class="text-xs text-blue-600">
                View Movements
            </a>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($products as $product)
                <div class="p-4 flex justify-between items-center">
                    <div>
                        <h4 class="font-medium">{{ $product->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold {{ $product->stock <= 5 ? 'text-red-600' : ($product->stock <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $product->stock }}
                        </p>
                        <p class="text-xs text-gray-500">in stock</p>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    No products found
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection