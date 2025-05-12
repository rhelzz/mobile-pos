@extends('layouts.mobile')

@section('title', 'Transaction History')
@section('header-title', 'Transaction History')

@section('content')
<div class="mb-4">
    <div x-data="{ showFilter: false }">
        <!-- Enhanced Header with Stats -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md p-4 mb-4 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold">Transactions</h2>
                    <p class="text-xs text-blue-100 mt-1">{{ $transactions->total() }} total transactions</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold">{{ $transactions->count() }}</p>
                    <p class="text-xs text-blue-100">displayed</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Button with Animation -->
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-medium text-gray-800">
                {{ request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all') ? 'Filtered Results' : 'All Transactions' }}
            </h2>
            <button @click="showFilter = !showFilter" 
                class="text-sm flex items-center px-3 py-1.5 rounded-full transition-all duration-200"
                :class="showFilter ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span x-text="showFilter ? 'Hide Filters' : 'Filter'"></span>
                {{ request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all') ? ' (Active)' : '' }}
            </button>
        </div>
        
        <!-- Improved Filter Panel -->
        <div x-show="showFilter" class="bg-white rounded-lg shadow-md p-4 mb-4" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-4">
            <form action="{{ route('transactions.index') }}" method="GET">
                <div class="space-y-3">
                    <!-- Date Range Selector with Improved Icons -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    type="date" 
                                    name="start_date" 
                                    value="{{ request('start_date') }}"
                                    class="pl-10 w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="Start"
                                >
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    type="date" 
                                    name="end_date" 
                                    value="{{ request('end_date') }}"
                                    class="pl-10 w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    placeholder="End"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Payment Method Filter with Icons -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <select name="payment_method" class="pl-10 w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="all" {{ request('payment_method') == 'all' ? 'selected' : '' }}>All Methods</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                <option value="midtrans" {{ request('payment_method') == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                                <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Payment Status Filter (New) -->
                    @if(request('payment_method') == 'midtrans' || !request()->has('payment_method'))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <select name="payment_status" class="pl-10 w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="all" {{ request('payment_status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="success" {{ request('payment_status') == 'success' ? 'selected' : '' }}>Success</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Better Button Layout -->
                <div class="flex justify-between mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('transactions.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                    <button type="submit" class="flex items-center px-5 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Enhanced Active Filter Summary -->
        @if(request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all') || (request()->has('payment_status') && request()->payment_status != 'all'))
        <div class="bg-blue-50 rounded-lg p-3 mb-3 text-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="font-medium text-blue-700">Filters:</span>
                    <div class="ml-2 text-blue-600">
                        @if(request()->has('start_date') || request()->has('end_date'))
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'All time' }} 
                                to 
                                {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Present' }}
                            </span>
                        @endif
                        
                        @if(request()->has('payment_method') && request()->payment_method != 'all')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                {{ ucfirst(request('payment_method')) }}
                            </span>
                        @endif
                        
                        @if(request()->has('payment_status') && request()->payment_status != 'all')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-1">
                                Status: {{ ucfirst(request('payment_status')) }}
                            </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="flex items-center text-blue-700 hover:text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
        @endif
        
        <!-- Enhanced Transaction List with Status Indicators -->
        <div class="space-y-3">
            @forelse($transactions as $transaction)
                <a href="{{ route('transactions.show', $transaction) }}" class="block bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow overflow-hidden border border-gray-100">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-blue-700">{{ $transaction->invoice_number }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $transaction->created_at->format('d M Y - H:i') }}
                                </p>
                                @if($transaction->customer_name)
                                <p class="text-xs text-gray-600 mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $transaction->customer_name }}
                                </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                                
                                <!-- Payment Method with Icon -->
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
                                    {{ ucfirst($transaction->payment_method) }}
                                </div>
                                
                                <!-- Midtrans Status Badge -->
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
                    <div class="bg-gray-50 px-4 py-2.5 text-xs flex justify-between items-center">
                        <span class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ $transaction->transactionItems->count() }} items
                        </span>
                        <span class="flex items-center text-blue-600 font-medium">
                            View Detail
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    </div>
                </a>
            @empty
                <!-- Improved Empty State -->
                <div class="bg-white rounded-lg shadow-md p-8 text-center border border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <p class="text-gray-500 mb-2">No transactions found</p>
                    <p class="text-gray-400 text-sm mb-4">{{ request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all') ? 'Try changing your filter settings' : 'Create your first transaction to get started' }}</p>
                    
                    @if(request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all'))
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear Filters
                        </a>
                    @else
                        <a href="{{ route('cashier') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create First Transaction
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
        
        <!-- Pagination with Better Design -->
        <div class="mt-4">
            {{ $transactions->links('pagination.simple-tailwind') }}
        </div>
        
        <!-- Quick Action Floating Button -->
        <div class="fixed bottom-20 right-5">
            <a href="{{ route('cashier') }}" class="flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- JS to update payment status -->
<script>
function updatePaymentStatus(transactionId) {
    if (confirm('Apakah pembayaran ini sudah berhasil?')) {
        fetch(`/transactions/${transactionId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: 'success' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center';
                toast.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Status pembayaran berhasil diubah</span>
                `;
                document.body.appendChild(toast);
                
                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        toast.remove();
                        location.reload();
                    }, 500);
                }, 3000);
            } else {
                alert('Gagal mengubah status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    }
}
</script>
@endsection