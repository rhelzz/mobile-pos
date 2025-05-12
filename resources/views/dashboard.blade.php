@extends('layouts.mobile')

@section('title', 'Dashboard')
@section('header-title', 'Dashboard')

@section('content')
    <!-- Date display -->
    <div class="mb-4 text-sm text-gray-500">
        <p>{{ now()->format('l, F j, Y') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Today's Sales</p>
            <p class="text-xl font-bold">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm font-medium text-gray-500">Total Products</p>
            <p class="text-xl font-bold">{{ $totalProducts }}</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200">
            <h3 class="font-medium">Sales Last 7 Days</h3>
        </div>
        <div class="p-4">
            <canvas id="salesChart" height="200"></canvas>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-medium">Low Stock Alert</h3>
                <a href="{{ route('stock.index') }}" class="text-xs text-blue-600">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($lowStockProducts->take(3) as $product)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                        </div>
                        <div class="{{ $product->stock <= 5 ? 'text-red-600' : 'text-yellow-600' }} font-semibold">
                            {{ $product->stock }} left
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-medium">Recent Transactions</h3>
            <a href="{{ route('transactions.index') }}" class="text-xs text-blue-600">View All</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentTransactions->take(5) as $transaction)
                <a href="{{ route('transactions.show', $transaction) }}" class="block p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ $transaction->invoice_number }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $transaction->created_at->format('M d, Y - H:i') }}
                                • {{ $transaction->transactionItems->count() }} items
                            </p>
                        </div>
                        <p class="font-semibold text-blue-600">
                            Rp {{ number_format($transaction->final_amount, 0, ',', '.') }}
                        </p>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-gray-500">
                    No recent transactions
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('cashier') }}" class="bg-blue-600 text-white rounded-lg shadow p-4 text-center">
            <svg class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <span class="font-medium">New Sale</span>
        </a>
        <a href="{{ route('stock.index') }}" class="bg-green-600 text-white rounded-lg shadow p-4 text-center">
            <svg class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
            </svg>
            <span class="font-medium">Manage Stock</span>
        </a>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach(array_keys($formattedSalesData) as $date)
                    '{{ \Carbon\Carbon::parse($date)->format('d M') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Sales',
                data: [
                    @foreach($formattedSalesData as $sales)
                        {{ $sales }},
                    @endforeach
                ],
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // For mobile, reduce the y-axis tick density
                        maxTicksLimit: 5,
                        // Format y-axis ticks as Rupiah
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                            } else {
                                return 'Rp ' + value;
                            }
                        }
                    }
                },
                x: {
                    ticks: {
                        // For mobile, show fewer x-axis labels
                        maxRotation: 0,
                        maxTicksLimit: 5
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        // Format tooltip values as Rupiah
                        label: function(context) {
                            let value = context.raw;
                            return 'Sales: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endpush