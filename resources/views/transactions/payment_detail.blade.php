@extends('layouts.mobile')

@section('title', 'Payment Detail')
@section('header-title', 'Payment Detail')

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
    </div>
    
    <div class="flex space-x-2">
        <a href="{{ route('transactions.index') }}" class="flex-1 block text-center py-2 px-4 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium">
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection