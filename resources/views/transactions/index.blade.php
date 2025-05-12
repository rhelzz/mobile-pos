@extends('layouts.mobile')

@section('title', 'Transaction History')
@section('header-title', 'Transaction History')

@section('content')
<div class="mb-4">
    <div x-data="{ showFilter: false }">
        <!-- Filter button -->
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-medium">All Transactions</h2>
            <button @click="showFilter = !showFilter" class="text-sm flex items-center px-3 py-1.5 bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter {{ request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all') ? '(Active)' : '' }}
            </button>
        </div>
        
        <!-- Filter panel - Improved for Mobile -->
        <div x-show="showFilter" class="bg-white rounded-lg shadow p-4 mb-4" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <form action="{{ route('transactions.index') }}" method="GET">
                <div class="space-y-3">
                    <!-- Date Range Selector -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
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
                    
                    <!-- Payment Method Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="all" {{ request('payment_method') == 'all' ? 'selected' : '' }}>All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-between mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('transactions.index') }}" class="flex items-center px-3 py-2 text-xs font-medium text-gray-700 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                    <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Filter Summary (if filters are active) -->
        @if(request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all'))
        <div class="bg-blue-50 rounded-lg p-3 mb-3 text-xs text-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <span class="font-medium">Active Filters:</span>
                    @if(request()->has('start_date') || request()->has('end_date'))
                        <span class="ml-1">
                            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'All time' }} 
                            to 
                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'Present' }}
                        </span>
                    @endif
                    @if(request()->has('payment_method') && request()->payment_method != 'all')
                        <span class="ml-1">| {{ ucfirst(request('payment_method')) }}</span>
                    @endif
                </div>
                <a href="{{ route('transactions.index') }}" class="flex items-center text-blue-700 hover:text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
        @endif
        
        <!-- Transactions list -->
        <div class="space-y-3">
            @forelse($transactions as $transaction)
                <a href="{{ route('transactions.show', $transaction) }}" class="block bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium">{{ $transaction->invoice_number }}</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $transaction->created_at->format('d M Y - H:i') }}
                                </p>
                                @if($transaction->customer_name)
                                <p class="text-xs text-gray-500 mt-1">
                                    Customer: {{ $transaction->customer_name }}
                                </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                                <p class="text-xs text-gray-500 flex items-center justify-end">
                                    @if($transaction->payment_method == 'cash')
                                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                    @elseif($transaction->payment_method == 'card')
                                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    @elseif($transaction->payment_method == 'transfer')
                                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                    {{ ucfirst($transaction->payment_method) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 text-xs text-gray-500 flex justify-between">
                        <span>{{ $transaction->transactionItems->count() }} items</span>
                        <span>View Details →</span>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500">No transactions found</p>
                    @if(request()->has('start_date') || request()->has('end_date') || (request()->has('payment_method') && request()->payment_method != 'all'))
                        <a href="{{ route('transactions.index') }}" class="mt-3 inline-block px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-medium">
                            Clear Filters
                        </a>
                    @else
                        <a href="{{ route('cashier') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                            Create First Transaction
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links('pagination.simple-tailwind') }}
        </div>
    </div>
</div>
@endsection