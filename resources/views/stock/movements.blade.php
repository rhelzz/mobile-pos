@extends('layouts.mobile')

@section('title', 'Stock Movements')
@section('header-title', 'Stock Movements')

@section('content')
<div class="mb-4">
    <div class="flex items-center mb-4">
        <h2 class="text-lg font-medium">Stock Movement History</h2>
        <a href="{{ route('stock.index') }}" class="ml-auto text-sm text-blue-600">
            Back to Stock
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
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
                                <span class="text-green-600 font-medium">+{{ $movement->quantity }}</span>
                            @else
                                <span class="text-red-600 font-medium">-{{ $movement->quantity }}</span>
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
                <div class="p-4 text-center text-gray-500">
                    No stock movements found
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection