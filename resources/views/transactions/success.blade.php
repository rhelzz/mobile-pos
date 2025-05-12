@extends('layouts.mobile')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="max-w-md mx-auto py-4 px-4">
    <!-- Success Header -->
    <div class="bg-green-600 rounded-t-lg py-6 px-4 text-center">
        <svg class="w-16 h-16 text-white mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h2 class="text-2xl font-bold text-white">Pembayaran Berhasil!</h2>
    </div>
    
    <!-- Transaction Details -->
    <div class="bg-white rounded-b-lg shadow-sm p-4 mb-4 border-t-0">
        <div class="flex justify-between items-center mb-3">
            <div>
                <p class="text-sm text-gray-500">No. Transaksi</p>
                <p class="font-bold">{{ $transaction->invoice_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Total</p>
                <p class="font-bold text-green-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="mt-3">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Metode Pembayaran:</span>
                <span>{{ ucfirst($transaction->midtrans_payment_type ?? 'Midtrans') }}</span>
            </div>
            
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Status:</span>
                <span class="bg-green-100 text-green-800 px-2 rounded-full text-xs font-semibold">Berhasil</span>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Waktu:</span>
                <span>{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
    
    <!-- Action buttons -->
    <div class="flex space-x-2">
        <a href="{{ route('transactions.index') }}" class="flex-1 block text-center py-2 px-4 bg-gray-200 text-gray-800 rounded-lg text-sm font-medium">
            Daftar Transaksi
        </a>
        <a href="{{ route('cashier') }}" class="flex-1 block text-center py-2 px-4 bg-green-600 text-white rounded-lg text-sm font-medium">
            Transaksi Baru
        </a>
    </div>
</div>
@endsection