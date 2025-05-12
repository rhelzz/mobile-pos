@extends('layouts.mobile')

@section('title', 'Pembayaran Berhasil')
@section('header-title', 'Pembayaran Berhasil')

@section('content')
<div class="mb-4 relative">
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 -mr-6 -mt-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round" class="text-green-100">
            <path d="M5.52 19c.64-2.2 1.84-3 3.22-3h6.52c1.38 0 2.58.8 3.22 3"/>
            <circle cx="12" cy="10" r="3"/>
            <circle cx="12" cy="12" r="10"/>
        </svg>
    </div>

    <!-- Success Header with Animation -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-t-lg py-8 px-4 text-center relative z-10 shadow-lg">
        <div class="mb-4 animate-bounce">
            <svg class="w-16 h-16 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-white">Pembayaran Berhasil!</h2>
        <p class="text-green-100 mt-2">Terima kasih atas pembayaran Anda</p>
    </div>
    
    <!-- Transaction Details Card -->
    <div class="bg-white rounded-b-lg shadow-md p-5 mb-4 border-t-0 relative z-10">
        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
            <div>
                <p class="text-sm text-gray-500">No. Transaksi</p>
                <p class="font-bold text-gray-800">{{ $transaction->invoice_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Total</p>
                <p class="font-bold text-xl text-green-600">Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="space-y-3 mb-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Metode Pembayaran:
                </span>
                <span class="font-medium text-gray-800">{{ ucfirst($transaction->midtrans_payment_type ?? 'Midtrans') }}</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-gray-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Tanggal:
                </span>
                <span class="font-medium text-gray-800">{{ $transaction->updated_at->format('d M Y') }}</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-gray-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Waktu:
                </span>
                <span class="font-medium text-gray-800">{{ $transaction->updated_at->format('H:i') }} WIB</span>
            </div>
            
            <div class="flex justify-between items-center">
                <span class="text-gray-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Status:
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Berhasil
                </span>
            </div>
        </div>
        
        <!-- Summary Box -->
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-600">
                    Pembayaran telah berhasil diproses. Terima kasih telah berbelanja di toko kami.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Action buttons with icons -->
    <div class="flex space-x-3">
        <a href="{{ route('transactions.index') }}" class="flex-1 flex items-center justify-center py-3 bg-gray-100 text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Daftar Transaksi
        </a>
        <a href="{{ route('cashier') }}" class="flex-1 flex items-center justify-center py-3 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Transaksi Baru
        </a>
    </div>
    
    <!-- Share Receipt Button -->
    <button onclick="shareReceipt()" class="flex items-center justify-center w-full mt-3 py-3 bg-blue-50 text-blue-600 border border-blue-100 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
        </svg>
        Bagikan Bukti Pembayaran
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confetti animation for success page
    createConfetti();
    
    // Add a success sound if desired
    playSuccessSound();
});

// Confetti animation
function createConfetti() {
    const colors = ['#4ade80', '#34d399', '#2dd4bf', '#22d3ee', '#60a5fa'];
    const numConfetti = 150;
    
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
        
        // Random shape (circle or rectangle)
        if (Math.random() > 0.5) {
            confetti.style.width = `${Math.random() * 10 + 5}px`;
            confetti.style.height = `${Math.random() * 5 + 3}px`;
            confetti.style.borderRadius = '0';
        } else {
            const size = Math.random() * 8 + 4;
            confetti.style.width = `${size}px`;
            confetti.style.height = `${size}px`;
            confetti.style.borderRadius = '50%';
        }
        
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.opacity = Math.random().toString();
        confetti.style.top = '-10px';
        confetti.style.left = `${Math.random() * 100}vw`;
        
        // Animation properties
        const duration = Math.random() * 3 + 2;
        const delay = Math.random() * 3;
        
        confetti.animate([
            { 
                transform: `translate3d(${Math.random() * 50 - 25}px, 0, 0) rotate(0deg)`,
                opacity: 1
            },
            { 
                transform: `translate3d(${Math.random() * 200 - 100}px, ${window.innerHeight + 10}px, 0) rotate(${Math.random() * 1000}deg)`,
                opacity: 0
            }
        ], {
            duration: duration * 1000,
            delay: delay * 1000,
            easing: 'cubic-bezier(0, .9, .57, 1)',
            fill: 'forwards'
        });
        
        container.appendChild(confetti);
        
        setTimeout(() => {
            confetti.remove();
        }, (duration + delay) * 1000);
    }
    
    setTimeout(() => {
        container.remove();
    }, 8000);
}

function playSuccessSound() {
    try {
        // Simple success sound using Web Audio API
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        // Create a short success sound
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        gainNode.gain.value = 0.2; // Low volume
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.setValueAtTime(1200, audioContext.currentTime + 0.1);
        
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 0.2);
    } catch (e) {
        console.log('Audio not supported or blocked');
    }
}

function shareReceipt() {
    // Create text to share
    const text = 
        'Pembayaran Berhasil\n' +
        '—————————————————\n' +
        'No. Transaksi: {{ $transaction->invoice_number }}\n' +
        'Total: Rp{{ number_format($transaction->final_amount, 0, ',', '.') }}\n' +
        'Tanggal: {{ $transaction->updated_at->format('d M Y H:i') }} WIB\n' +
        'Metode: {{ ucfirst($transaction->midtrans_payment_type ?? 'Midtrans') }}\n' +
        '—————————————————\n';
    
    // Use Web Share API if available
    if (navigator.share) {
        navigator.share({
            title: 'Bukti Pembayaran',
            text: text
        }).catch(err => {
            // Fallback - copy to clipboard
            copyToClipboard(text);
        });
    } else {
        // Fallback - copy to clipboard
        copyToClipboard(text);
    }
}

function copyToClipboard(text) {
    // Copy text to clipboard
    navigator.clipboard.writeText(text).then(() => {
        // Show toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center z-50';
        toast.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span>Bukti pembayaran disalin ke clipboard!</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 3000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endsection