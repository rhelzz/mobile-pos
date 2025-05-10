@extends('layouts.mobile')

@section('title', 'Transaction Detail')
@section('header-title', 'Transaction Detail')

@section('content')
<div class="mb-4">
    <!-- Transaction header -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-medium">{{ $transaction->invoice_number }}</h3>
                <p class="text-xs text-gray-500">
                    {{ $transaction->created_at->format('d M Y - H:i') }}
                </p>
            </div>
            <div class="text-right">
                <span class="font-semibold text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                <p class="text-xs text-gray-500">{{ ucfirst($transaction->payment_method) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Items list -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
        <div class="p-3 border-b border-gray-100">
            <h3 class="font-medium">Items</h3>
        </div>
        
        <div class="divide-y divide-gray-100">
            @foreach($transaction->transactionItems as $item)
                <div class="p-3 flex justify-between items-center">
                    <div class="flex-1">
                        <p class="font-medium">{{ $item->product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Transaction summary -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax</span>
                <span>Rp{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
            </div>
            
            @if($transaction->discount_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Discount</span>
                    <span class="text-red-500">- Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            
            <div class="border-t border-gray-200 pt-2 mt-2">
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span class="text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional information -->
    @if($transaction->notes)
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
            <h3 class="font-medium mb-1">Notes</h3>
            <p class="text-sm text-gray-600">{{ $transaction->notes }}</p>
        </div>
    @endif
    
    <!-- Action buttons -->
    <div class="flex space-x-2">
        <a href="{{ route('transactions.index') }}" class="flex-1 block text-center py-2 px-4 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium">
            Back to List
        </a>
        <a href="{{ route('cashier') }}" class="flex-1 block text-center py-2 px-4 bg-blue-600 text-white rounded-lg text-sm font-medium">
            New Transaction
        </a>
    </div>
</div>
@endsection