@extends('layouts.mobile')

@section('title', 'Transaction History')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Transaction History</h1>
        <a href="{{ route('cashier') }}" class="bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
            New Transaction
        </a>
    </div>
    
    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('transactions.index') }}" class="space-y-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by invoice or customer..."
                        value="{{ request('search') }}" 
                        class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                    <div class="flex space-x-2 items-center">
                        <input 
                            type="date" 
                            name="from_date" 
                            value="{{ request('from_date') }}" 
                            class="rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                        <span class="text-gray-500">to</span>
                        <input 
                            type="date" 
                            name="to_date" 
                            value="{{ request('to_date') }}" 
                            class="rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        >
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <!-- Payment Method Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="all" {{ request('payment_method') == 'all' ? 'selected' : '' }}>All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="midtrans" {{ request('payment_method') == 'midtrans' ? 'selected' : '' }}>Midtrans</option>
                        <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Midtrans Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Status</label>
                    <select name="payment_status" class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="all" {{ request('payment_status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="success" {{ request('payment_status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                
                <div class="flex-1 flex items-end">
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                            Filter
                        </button>
                        <a href="{{ route('transactions.index') }}" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Transaction List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Invoice
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment Info
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium text-blue-600 hover:text-blue-900">
                                <a href="{{ route('transactions.show', $transaction) }}">
                                    {{ $transaction->invoice_number }}
                                </a>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $transaction->user->name ?? 'Unknown User' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $transaction->created_at->format('d M Y') }}
                            <div class="text-xs">
                                {{ $transaction->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $transaction->customer_name ?: 'General Customer' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium">
                                Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $transaction->transactionItems->count() }} items
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                {{ ucfirst($transaction->payment_method) }}
                                
                                @if($transaction->payment_method == 'midtrans')
                                    <div class="mt-1">
                                        @if($transaction->midtrans_transaction_status == 'success')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Success
                                            </span>
                                        @elseif($transaction->midtrans_transaction_status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif(in_array($transaction->midtrans_transaction_status, ['deny', 'expire', 'cancel']))
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ ucfirst($transaction->midtrans_transaction_status) }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm space-x-2">
                            <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900">
                                Detail
                            </a>
                            
                            @if($transaction->payment_method == 'midtrans')
                                <a href="{{ route('transactions.payment', $transaction) }}" class="text-blue-600 hover:text-blue-900 ml-2">
                                    Payment
                                </a>
                            @endif
                            
                            @if($transaction->payment_method == 'midtrans' && $transaction->midtrans_transaction_status == 'pending')
                                <div class="mt-1">
                                    <button 
                                        type="button" 
                                        onclick="updatePaymentStatus('{{ $transaction->id }}')" 
                                        class="text-xs bg-yellow-100 text-yellow-800 py-1 px-2 rounded-full hover:bg-yellow-200"
                                    >
                                        Update Status
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No transactions found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->isNotEmpty())
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
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
                alert('Status pembayaran berhasil diubah menjadi success');
                location.reload();
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
@endpush
@endsection