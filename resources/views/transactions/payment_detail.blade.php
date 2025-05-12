@extends('layouts.mobile')

@section('title', 'Payment Details')

@section('content')
<div class="max-w-md mx-auto py-4 px-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold">Payment Details</h1>
    </div>
    
    <!-- Transaction Header -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm text-blue-600 font-medium">
                    {{ $transaction->invoice_number }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ $transaction->created_at->format('d M Y, H:i') }}
                </p>
                @if($transaction->customer_name)
                <p class="text-sm text-gray-700 mt-1">
                    <span class="font-medium">Customer:</span> {{ $transaction->customer_name }}
                </p>
                @endif
            </div>
            <div class="text-right">
                <span class="font-semibold text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                <p class="text-xs text-gray-500">{{ ucfirst($transaction->payment_method) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Payment Status -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <h3 class="font-medium mb-3">Payment Status</h3>
        
        @if($transaction->midtrans_transaction_status == 'success')
            <div class="bg-green-100 text-green-700 p-3 rounded-lg">
                Pembayaran Selesai
            </div>
        @elseif($transaction->midtrans_transaction_status == 'pending')
            <div class="bg-yellow-100 text-yellow-700 p-3 rounded-lg">
                Menunggu Pembayaran
            </div>
        @elseif(in_array($transaction->midtrans_transaction_status, ['deny', 'expire', 'cancel']))
            <div class="bg-red-100 text-red-700 p-3 rounded-lg">
                Pembayaran {{ ucfirst($transaction->midtrans_transaction_status) }}
            </div>
        @endif
        
        <div class="mt-3 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Transaction ID:</span>
                <span>{{ $transaction->midtrans_transaction_id ?? '-' }}</span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Payment Type:</span>
                <span>{{ ucfirst($transaction->midtrans_payment_type ?? '-') }}</span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Transaction Time:</span>
                <span>{{ $transaction->midtrans_transaction_time ?? '-' }}</span>
            </div>
            
            @if($transaction->midtrans_va_number)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">VA Number:</span>
                <span>{{ $transaction->midtrans_va_number }}</span>
            </div>
            @endif
            
            @if($transaction->midtrans_payment_code)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Payment Code:</span>
                <span>{{ $transaction->midtrans_payment_code }}</span>
            </div>
            @endif
        </div>
        
        @if($transaction->midtrans_pdf_url && $transaction->midtrans_transaction_status == 'pending')
        <a href="{{ $transaction->midtrans_pdf_url }}" target="_blank" class="block w-full mt-4 py-2 bg-blue-600 text-white text-center rounded-lg">
            Download Instruksi Pembayaran
        </a>
        @endif
        
        @if($transaction->midtrans_transaction_status == 'pending')
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
            <h4 class="font-medium text-sm mb-2">Update Payment Status</h4>
            <p class="text-xs text-gray-600 mb-3">Pembayaran ini masih dalam status menunggu. Jika pembayaran telah dilakukan, Anda dapat memperbarui statusnya secara manual.</p>
            
            <form action="{{ route('transactions.update-status', $transaction) }}" method="POST" class="flex space-x-2">
                @csrf
                <select name="status" class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="success">Berhasil (Success)</option>
                    <option value="cancel">Dibatalkan (Cancel)</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white text-sm py-2 px-3 rounded-lg">
                    Update Status
                </button>
            </form>
        </div>
        @endif
        
        @if($transaction->midtrans_transaction_status == 'success')
        <div class="mt-2">
            <a href="{{ route('midtrans.success', $transaction) }}" class="block w-full py-2 bg-green-600 text-white text-center rounded-lg">
                View Success Details
            </a>
        </div>
        @endif
    </div>
    
    <div class="flex space-x-2">
        <a href="{{ route('transactions.index') }}" class="flex-1 block text-center py-2 px-4 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium">
            Kembali ke Daftar
        </a>
    </div>
</div>

@if($transaction->midtrans_transaction_status == 'pending')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
async function retryPayment(transactionId) {
    try {
        const response = await fetch('{{ route('midtrans.token') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ transaction_id: transactionId })
        });
        
        const tokenResult = await response.json();
        
        if (tokenResult.success && tokenResult.snap_token) {
            window.snap.pay(tokenResult.snap_token, {
                onSuccess: (result) => {
                    window.location.href = '{{ url("/midtrans/success") }}/' + transactionId;
                },
                onPending: (result) => {
                    alert("Menunggu pembayaran Anda!");
                    location.reload();
                },
                onError: (result) => {
                    alert("Pembayaran gagal: " + (result.message || 'Unknown error'));
                },
                onClose: () => {
                    alert("Anda menutup popup tanpa menyelesaikan pembayaran");
                }
            });
        } else {
            alert('Failed to get payment token: ' + (tokenResult.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred: ' + error.message);
    }
}
</script>
@endif
@endsection