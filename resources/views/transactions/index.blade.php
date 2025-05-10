@extends('layouts.mobile')

@section('title', 'Transaction History')
@section('header-title', 'Transaction History')

@section('content')
<div class="mb-4">
    <div x-data="{ showFilter: false }">
        <!-- Filter button -->
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-medium">All Transactions</h2>
            <button @click="showFilter = !showFilter" class="text-sm flex items-center text-gray-600">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
        </div>
        
        <!-- Filter panel -->
        <div x-show="showFilter" class="bg-white rounded-lg shadow p-4 mb-4" x-cloak>
            <form action="{{ route('transactions.index') }}" method="GET">
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                        <input 
                            type="date" 
                            name="start_date" 
                            value="{{ request('start_date') }}"
                            class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                        <input 
                            type="date" 
                            name="end_date" 
                            value="{{ request('end_date') }}"
                            class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <a href="{{ route('transactions.index') }}" class="px-3 py-1 text-xs font-medium text-gray-700 hover:text-gray-500 mr-2">
                        Reset
                    </a>
                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700">
                        Apply Filter
                    </button>
                </div>
            </form>
        </div>
        
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
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                                <p class="text-xs text-gray-500">{{ ucfirst($transaction->payment_method) }}</p>
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
                    <svg class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <p class="text-gray-500">No transactions found</p>
                    <a href="{{ route('cashier') }}" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                        Create First Transaction
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection