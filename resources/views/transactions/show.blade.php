@extends('layouts.mobile')

@section('title', 'Transaction Detail')
@section('header-title', 'Transaction Detail')

@section('content')
<div class="mb-4">
    <!-- Enhanced Transaction Header -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-24 h-24 transform translate-x-8 -translate-y-8 bg-blue-50 rounded-full opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 transform -translate-x-4 translate-y-4 bg-blue-50 rounded-full opacity-50"></div>
        
        <!-- Transaction Info with Better Layout -->
        <div class="flex justify-between items-start relative z-10">
            <div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="font-semibold text-blue-700">{{ $transaction->invoice_number }}</h3>
                </div>
                <div class="flex items-center mt-1 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $transaction->created_at->format('d M Y - H:i') }}
                </div>
                
                <!-- Customer info with icon -->
                @if($transaction->customer_name)
                <div class="flex items-center mt-2 text-sm text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">{{ $transaction->customer_name }}</span>
                </div>
                @endif
            </div>
            
            <!-- Price and Payment Method with Visual Indicators -->
            <div class="text-right">
                <span class="font-bold text-xl text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                <div class="flex items-center justify-end text-xs text-gray-500 mt-1">
                    @if($transaction->payment_method == 'cash')
                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    @elseif($transaction->payment_method == 'card')
                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    @elseif($transaction->payment_method == 'transfer')
                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    @elseif($transaction->payment_method == 'midtrans')
                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    @else
                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                    <span>{{ ucfirst($transaction->payment_method) }}</span>
                </div>
                
                <!-- Midtrans Payment Status Badge -->
                @if($transaction->payment_method == 'midtrans')
                    <div class="mt-2">
                        @if($transaction->midtrans_transaction_status == 'success')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Success
                            </span>
                        @elseif($transaction->midtrans_transaction_status == 'pending')
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Pending
                            </span>
                        @elseif(in_array($transaction->midtrans_transaction_status, ['deny', 'expire', 'cancel']))
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ ucfirst($transaction->midtrans_transaction_status) }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Enhanced Items List with Better Visual Hierarchy -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
        <div class="p-3 bg-gray-50 border-b border-gray-100 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="font-medium text-gray-700">Items ({{ $transaction->transactionItems->count() }})</h3>
        </div>
        
        <div class="divide-y divide-gray-100">
            @foreach($transaction->transactionItems as $item)
                <div class="p-4 hover:bg-blue-50 transition-colors">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $item->quantity }} × Rp{{ number_format($item->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-blue-700">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Transaction Summary with Improved Layout -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <h3 class="font-medium text-gray-700 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Summary
        </h3>
        
        <div class="space-y-2 bg-gray-50 rounded-lg p-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax</span>
                <span class="font-medium">Rp{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
            </div>
            
            @if($transaction->discount_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Discount</span>
                    <span class="text-red-500 font-medium">- Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            
            <div class="border-t border-gray-200 pt-2 mt-2">
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span class="text-blue-600 text-lg">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer & Additional Information with Better Styling -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <!-- Customer Information Section -->
        @if($transaction->customer_name)
        <div class="mb-3">
            <h3 class="font-medium mb-2 flex items-center text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Customer Information
            </h3>
            <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                <p class="text-sm text-gray-700">{{ $transaction->customer_name }}</p>
            </div>
        </div>
        @endif
        
        <!-- Notes Section -->
        @if($transaction->notes)
        <div class="{{ $transaction->customer_name ? 'mt-4 pt-4 border-t border-gray-100' : '' }}">
            <h3 class="font-medium mb-2 flex items-center text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Notes
            </h3>
            <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-100">
                <p class="text-sm text-gray-700">{{ $transaction->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Midtrans Payment Button with Animation -->
    @if($transaction->payment_method == 'midtrans')
    <div class="mt-4" x-data="{ hover: false }">
        <a href="{{ route('transactions.payment', $transaction) }}" 
           class="block w-full text-center py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-sm font-medium shadow-md transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg relative overflow-hidden"
           @mouseenter="hover = true" @mouseleave="hover = false">
            <div class="relative z-10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Lihat Detail Pembayaran
            </div>
            <div class="absolute inset-0 bg-white opacity-20 transform -translate-x-full" 
                 :class="{ 'animate-shine': hover }"></div>
        </a>
    </div>
    @endif
    
    <!-- Action Buttons with Better Design -->
    <div class="flex space-x-3 mt-4">
        <a href="{{ route('transactions.index') }}" class="flex-1 flex items-center justify-center py-3 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to List
        </a>
        <a href="{{ route('cashier') }}" class="flex-1 flex items-center justify-center py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Transaction
        </a>
    </div>
</div>

<style>
@keyframes shine {
    100% {
        transform: translateX(100%);
    }
}
.animate-shine {
    animation: shine 0.8s;
}
</style>

<script>
// Add subtle animation to items when page loads
document.addEventListener('DOMContentLoaded', function() {
    const items = document.querySelectorAll('.divide-y > div');
    
    items.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(10px)';
        item.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 100 + (index * 50)); // Staggered animation
    });
});
</script>
@endsection