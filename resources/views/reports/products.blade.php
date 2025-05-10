@extends('layouts.mobile')

@section('title', 'Product Reports')
@section('header-title', 'Product Reports')

@section('content')
<div class="mb-4">
    <!-- Date filter -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <form action="{{ route('reports.products') }}" method="GET">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        value="{{ $startDate }}"
                        class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        value="{{ $endDate }}"
                        class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Most popular products -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <h3 class="font-medium mb-3">Most Popular Products</h3>
        <div class="space-y-3">
            @forelse($popularProducts as $item)
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-2 font-medium text-sm">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-grow">
                        <p class="font-medium">{{ $item->product->name }}</p>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Sold: {{ $item->total_quantity }}</span>
                            <span>Rp{{ number_format($item->total_sales, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-2">No data available</p>
            @endforelse
        </div>
    </div>
    
    <!-- Most profitable products -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <h3 class="font-medium mb-3">Most Profitable Products</h3>
        <div class="space-y-3">
            @forelse($profitableProducts as $item)
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-2 font-medium text-sm">
                        {{ $loop->iteration }}
                    </div>
                    <div class="flex-grow">
                        <p class="font-medium">{{ $item->product->name }}</p>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Sold: {{ $item->total_quantity }}</span>
                            <span>Rp{{ number_format($item->total_sales, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-2">No data available</p>
            @endforelse
        </div>
    </div>
    
    <!-- Report links -->
    <div class="flex space-x-3">
        <a href="{{ route('reports.sales') }}" class="flex-1 block text-center py-2 px-4 bg-white shadow-sm text-blue-600 rounded-lg text-sm font-medium border border-blue-100">
            Sales Report
        </a>
        <a href="{{ route('reports.hourly') }}" class="flex-1 block text-center py-2 px-4 bg-white shadow-sm text-blue-600 rounded-lg text-sm font-medium border border-blue-100">
            Hourly Report
        </a>
    </div>
</div>
@endsection