@extends('layouts.mobile')

@section('title', 'Detail Pembayaran')
@section('header-title', 'Detail Pembayaran')

@section('content')
<div class="mb-4">
    <!-- Transaction Header with Payment Method Icon -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4 relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute top-0 right-0 w-24 h-24 transform translate-x-8 -translate-y-8 bg-blue-100 rounded-full opacity-30"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 transform -translate-x-4 translate-y-4 bg-blue-100 rounded-full opacity-30"></div>
        
        <div class="flex justify-between items-start relative z-10">
            <div>
                <h3 class="font-semibold text-blue-700">{{ $transaction->invoice_number }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $transaction->created_at->format('d M Y - H:i') }}
                </p>
                @if($transaction->customer_name)
                <p class="text-sm text-gray-700 mt-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">{{ $transaction->customer_name }}</span>
                </p>
                @endif
            </div>
            <div class="text-right">
                <span class="font-bold text-xl text-blue-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</span>
                <div class="flex items-center justify-end text-sm text-gray-600 mt-1">
                    @if($transaction->payment_method == 'midtrans')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    @endif
                    {{ ucfirst($transaction->payment_method) }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Payment Status with Better Visual Indicators -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-medium text-gray-800">Status Pembayaran</h3>
            
            <!-- Status Badge -->
            @if($transaction->midtrans_transaction_status == 'success')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Selesai
                </span>
            @elseif($transaction->midtrans_transaction_status == 'pending')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <svg class="animate-spin -ml-1 mr-2 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menunggu
                </span>
            @elseif($transaction->midtrans_transaction_status == 'deny')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Ditolak
                </span>
            @elseif($transaction->midtrans_transaction_status == 'expire')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Kedaluwarsa
                </span>
            @elseif($transaction->midtrans_transaction_status == 'cancel')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Dibatalkan
                </span>
            @endif
        </div>
        
        <!-- Interactive Status Timeline -->
        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-between">
                <div class="flex items-center">
                    <span class="relative flex h-6 w-6 items-center justify-center rounded-full {{ $transaction->created_at ? 'bg-blue-600' : 'bg-gray-300' }}">
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="ml-1 text-xs text-gray-500">Dibuat</span>
                </div>
                
                <div class="flex items-center">
                    <span class="relative flex h-6 w-6 items-center justify-center rounded-full {{ in_array($transaction->midtrans_transaction_status, ['pending', 'success', 'deny', 'expire', 'cancel']) ? 'bg-blue-600' : 'bg-gray-300' }}">
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span class="ml-1 text-xs text-gray-500">Diproses</span>
                </div>
                
                <div class="flex items-center">
                    <span class="relative flex h-6 w-6 items-center justify-center rounded-full {{ $transaction->midtrans_transaction_status == 'success' ? 'bg-green-600' : ($transaction->midtrans_transaction_status == 'pending' ? 'bg-yellow-400' : ($transaction->midtrans_transaction_status ? 'bg-red-500' : 'bg-gray-300')) }}">
                        @if($transaction->midtrans_transaction_status == 'success')
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        @elseif($transaction->midtrans_transaction_status == 'pending')
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        @elseif($transaction->midtrans_transaction_status)
                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        @else
                        <span class="h-3 w-3"></span>
                        @endif
                    </span>
                    <span class="ml-1 text-xs text-gray-500">{{ $transaction->midtrans_transaction_status == 'success' ? 'Selesai' : ($transaction->midtrans_transaction_status == 'pending' ? 'Menunggu' : 'Gagal') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Card-style Payment Details -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 shadow-inner">
            <div class="space-y-3">
                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                    <span class="text-gray-600 text-sm">No. Transaksi:</span>
                    <span class="font-medium text-gray-800">{{ $transaction->midtrans_transaction_id ?? $transaction->invoice_number }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Metode Pembayaran:</span>
                    <span class="font-medium text-gray-800">{{ ucfirst($transaction->midtrans_payment_type ?? 'Midtrans') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Waktu Transaksi:</span>
                    <span class="font-medium text-gray-800">{{ $transaction->midtrans_transaction_time ? \Carbon\Carbon::parse($transaction->midtrans_transaction_time)->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i') }}</span>
                </div>
                
                @if($transaction->midtrans_va_number)
                <div class="mt-2 pt-2 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Virtual Account:</span>
                        <div class="flex items-center">
                            <span id="va-number" class="font-medium text-gray-800">{{ $transaction->midtrans_va_number }}</span>
                            <button onclick="copyToClipboard('va-number')" class="ml-2 text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($transaction->midtrans_payment_code)
                <div class="mt-2 pt-2 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Kode Pembayaran:</span>
                        <div class="flex items-center">
                            <span id="payment-code" class="font-medium text-gray-800">{{ $transaction->midtrans_payment_code }}</span>
                            <button onclick="copyToClipboard('payment-code')" class="ml-2 text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Action Buttons for Pending Payments -->
        @if($transaction->midtrans_transaction_status == 'pending')
            <!-- PDF Download Button -->
            @if($transaction->midtrans_pdf_url)
            <a href="{{ $transaction->midtrans_pdf_url }}" target="_blank" class="flex items-center justify-center w-full mt-4 py-3 bg-blue-600 text-white text-center rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                Download Instruksi Pembayaran
            </a>
            @endif
            
            <!-- Update Status Form with Better UX -->
            <div class="mt-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <div class="flex items-start mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-medium text-sm text-yellow-800 mb-1">Pembayaran Menunggu</h4>
                        <p class="text-xs text-yellow-700">Jika Anda sudah melakukan pembayaran tetapi status belum berubah, Anda dapat memperbarui status secara manual.</p>
                    </div>
                </div>
                
                <form action="{{ route('transactions.update-status', $transaction) }}" method="POST">
                    @csrf
                    <div class="flex space-x-2">
                        <select name="status" class="flex-1 rounded-lg border-yellow-300 text-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 bg-white">
                            <option value="success">Pembayaran Berhasil</option>
                            <option value="cancel">Batalkan Pembayaran</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                            Update Status
                        </button>
                    </div>
                </form>
                
                <!-- Retry Payment Button -->
                <button onclick="retryPayment('{{ $transaction->id }}')" class="flex items-center justify-center w-full mt-3 py-2 bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Bayar Lagi
                </button>
            </div>
        @endif
        
        <!-- Success Button -->
        @if($transaction->midtrans_transaction_status == 'success')
            <a href="{{ route('midtrans.success', $transaction) }}" class="flex items-center justify-center w-full mt-4 py-3 bg-green-600 text-white text-center rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Lihat Detail Pembayaran Berhasil
            </a>
        @endif
        
        <!-- Failed Payment Retry (for denied, expired transactions) -->
        @if(in_array($transaction->midtrans_transaction_status, ['deny', 'expire', 'cancel']))
            <div class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                <div class="flex items-start mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-medium text-sm text-red-800">Pembayaran Gagal</h4>
                        <p class="text-xs text-red-700 mt-1">Status pembayaran: {{ ucfirst($transaction->midtrans_transaction_status) }}</p>
                    </div>
                </div>
                
                <button onclick="retryPayment('{{ $transaction->id }}')" class="flex items-center justify-center w-full py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Coba Bayar Lagi
                </button>
            </div>
        @endif
    </div>
    
    <!-- Action Button Row -->
    <div class="flex space-x-2">
        <a href="{{ route('transactions.index') }}" class="flex-1 flex items-center justify-center py-3 px-4 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar
        </a>
        
        <a href="{{ route('transactions.show', $transaction) }}" class="flex-1 flex items-center justify-center py-3 px-4 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            Lihat Transaksi
        </a>
    </div>
</div>

@if($transaction->midtrans_transaction_status == 'pending' || in_array($transaction->midtrans_transaction_status, ['deny', 'expire', 'cancel']))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(() => {
        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-2 rounded shadow-lg flex items-center z-50';
        toast.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Disalin ke clipboard!</span>
        `;
        document.body.appendChild(toast);
        
        // Remove toast after 2 seconds
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

// Retry payment function
async function retryPayment(transactionId) {
    try {
        // Show loading indicator
        const loadingToast = document.createElement('div');
        loadingToast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-2 rounded shadow-lg flex items-center z-50';
        loadingToast.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memproses pembayaran...</span>
        `;
        document.body.appendChild(loadingToast);
        
        // Request token dari server
        const response = await fetch('{{ route('midtrans.token') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ transaction_id: transactionId })
        });
        
        // Remove loading indicator
        loadingToast.remove();
        
        const tokenResult = await response.json();
        
        if (tokenResult.success && tokenResult.snap_token) {
            // Open Midtrans Snap payment page
            window.snap.pay(tokenResult.snap_token, {
                onSuccess: (result) => {
                    showNotification('success', 'Pembayaran berhasil!');
                    setTimeout(() => {
                        window.location.href = '{{ url("/midtrans/success") }}/' + transactionId;
                    }, 1500);
                },
                onPending: (result) => {
                    showNotification('warning', 'Menunggu pembayaran Anda');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                onError: (result) => {
                    showNotification('error', 'Pembayaran gagal: ' + (result.message || 'Terjadi kesalahan'));
                },
                onClose: () => {
                    showNotification('info', 'Pembayaran dibatalkan');
                }
            });
        } else {
            showNotification('error', 'Gagal mendapatkan token: ' + (tokenResult.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan: ' + error.message);
    }
}

// Show toast notification
function showNotification(type, message) {
    // Set colors based on notification type
    let bgColor = 'bg-blue-600';
    let icon = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    `;
    
    if (type === 'success') {
        bgColor = 'bg-green-600';
        icon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
    } else if (type === 'error') {
        bgColor = 'bg-red-600';
        icon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        `;
    } else if (type === 'warning') {
        bgColor = 'bg-yellow-500';
        icon = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        `;
    }
    
    // Create and show toast
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 left-1/2 transform -translate-x-1/2 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg flex items-center z-50`;
    toast.innerHTML = `${icon}<span>${message}</span>`;
    
    document.body.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
        setTimeout(() => {
            toast.remove();
        }, 500);
    }, 3000);
}
</script>
@endif

<!-- Success Page View -->
@if($transaction->midtrans_transaction_status == 'success')
<script>
// Add confetti animation for successful payments
document.addEventListener('DOMContentLoaded', function() {
    const colors = ['#4299e1', '#38b2ac', '#4c51bf', '#ed8936', '#48bb78'];
    const numConfetti = 100;
    
    const container = document.createElement('div');
    container.style.position = 'fixed';
    container.style.top = '0';
    container.style.left = '0';
    container.style.width = '100%';
    container.style.height = '100%';
    container.style.pointerEvents = 'none';
    container.style.zIndex = '40';
    document.body.appendChild(container);
    
    for (let i = 0; i < numConfetti; i++) {
        const confetti = document.createElement('div');
        confetti.style.position = 'absolute';
        confetti.style.width = `${Math.random() * 10 + 5}px`;
        confetti.style.height = `${Math.random() * 5 + 3}px`;
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.opacity = Math.random().toString();
        confetti.style.borderRadius = '50%';
        confetti.style.top = '-10px';
        confetti.style.left = `${Math.random() * 100}vw`;
        confetti.style.transform = 'rotate(0deg)';
        
        // Animation properties
        const duration = Math.random() * 3 + 2;
        const delay = Math.random() * 2;
        
        confetti.animate([
            { 
                transform: `translate3d(0, 0, 0) rotate(0deg)`,
                opacity: 1
            },
            { 
                transform: `translate3d(${Math.random() * 100 - 50}px, ${window.innerHeight}px, 0) rotate(${Math.random() * 360}deg)`,
                opacity: 0
            }
        ], {
            duration: duration * 1000,
            delay: delay * 1000,
            easing: 'cubic-bezier(0, .9, .57, 1)',
            fill: 'forwards'
        });
        
        container.appendChild(confetti);
        
        // Remove confetti element after animation
        setTimeout(() => {
            confetti.remove();
        }, (duration + delay) * 1000);
    }
    
    // Remove container after all animations
    setTimeout(() => {
        container.remove();
    }, 5000);
});
</script>
@endif
@endsection