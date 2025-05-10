@extends('layouts.mobile')

@section('title', 'Sales Reports')
@section('header-title', 'Sales Reports')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="mb-4">
    <!-- Date filter -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <form action="{{ route('reports.sales') }}" method="GET">
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                    <input 
                        type="date" 
                        name="start_date" 
                        value="{{ $startDate }}"
                        class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                    <input 
                        type="date" 
                        name="end_date" 
                        value="{{ $endDate }}"
                        class="w-full rounded-lg text-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    >
                </div>
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Summary cards -->
    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-3">
            <p class="text-xs text-gray-500 mb-1">Total Sales</p>
            <p class="text-lg font-bold">Rp{{ number_format($summary['total_sales'], 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-3">
            <p class="text-xs text-gray-500 mb-1">Transactions</p>
            <p class="text-lg font-bold">{{ $summary['total_transactions'] }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-3">
            <p class="text-xs text-gray-500 mb-1">Average Sale</p>
            <p class="text-lg font-bold">Rp{{ number_format($summary['avg_sale'], 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Sales chart -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <h3 class="font-medium mb-3">Daily Sales</h3>
        <div class="h-48">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </div>
    
    <!-- Payment methods -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <h3 class="font-medium mb-3">Payment Methods</h3>
        <div class="h-40">
            <canvas id="paymentMethodChart"></canvas>
        </div>
    </div>
    
    <!-- Report links -->
    <div class="flex space-x-3">
        <a href="{{ route('reports.products') }}" class="flex-1 block text-center py-2 px-4 bg-white shadow-sm text-blue-600 rounded-lg text-sm font-medium border border-blue-100">
            Product Report
        </a>
        <a href="{{ route('reports.hourly') }}" class="flex-1 block text-center py-2 px-4 bg-white shadow-sm text-blue-600 rounded-lg text-sm font-medium border border-blue-100">
            Hourly Report
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Daily sales chart
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySalesChart = new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($dailySales as $sale)
                    '{{ \Carbon\Carbon::parse($sale->date)->format('d/m') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Sales',
                data: [
                    @foreach($dailySales as $sale)
                        {{ $sale->total }},
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
                        maxTicksLimit: 5
                    }
                },
                x: {
                    ticks: {
                        maxTicksLimit: 5
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Payment methods chart
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentMethodChart = new Chart(paymentMethodCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($paymentMethods as $method)
                    '{{ ucfirst($method->payment_method) }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($paymentMethods as $method)
                        {{ $method->total }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(99, 102, 241, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>
@endpush