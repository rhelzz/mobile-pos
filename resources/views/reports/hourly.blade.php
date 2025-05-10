@extends('layouts.mobile')

@section('title', 'Hourly Analysis')
@section('header-title', 'Hourly Analysis')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="mb-4">
    <!-- Date filter -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <form action="{{ route('reports.hourly') }}" method="GET">
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
    
    <!-- Highlight cards -->
    <div class="grid grid-cols-2 gap-3 mb-4">
        @if($busiestHour)
            <div class="bg-white rounded-lg shadow-sm p-3">
                <p class="text-xs text-gray-500 mb-1">Busiest Hour</p>
                <p class="text-lg font-bold">{{ sprintf('%02d:00', $busiestHour->hour) }}</p>
                <p class="text-xs text-gray-500">{{ $busiestHour->count }} transactions</p>
            </div>
        @endif
        
        @if($mostProfitableHour)
            <div class="bg-white rounded-lg shadow-sm p-3">
                <p class="text-xs text-gray-500 mb-1">Most Profitable</p>
                <p class="text-lg font-bold">{{ sprintf('%02d:00', $mostProfitableHour->hour) }}</p>
                <p class="text-xs text-gray-500">Rp{{ number_format($mostProfitableHour->total, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>
    
    <!-- Hourly chart -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <h3 class="font-medium mb-3">Hourly Sales Distribution</h3>
        <div class="h-48">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>
    
    <!-- Hourly data table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
        <div class="p-3 border-b border-gray-200">
            <h3 class="font-medium">Hourly Breakdown</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Hour</th>
                        <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Transactions</th>
                        <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Sales</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($formattedHourlySales as $hour => $data)
                        @if($data['count'] > 0)
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $data['hour'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 text-right">
                                    {{ $data['count'] }}
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 text-right">
                                    Rp{{ number_format($data['total'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Report links -->
    <div class="flex space-x-3">
        <a href="{{ route('reports.sales') }}" class="flex-1 block text-center py-2 px-4 bg-white shadow-sm text-blue-600 rounded-lg text-sm font-medium border border-blue-100">
            Sales Report
        </a>
        <a href="{{ route('reports.products') }}" class="flex-1 block text-center py-2 px-4 bg-white shadow-sm text-blue-600 rounded-lg text-sm font-medium border border-blue-100">
            Product Report
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hourly chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const hourlyChart = new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($formattedHourlySales as $data)
                    '{{ $data["hour"] }}',
                @endforeach
            ],
            datasets: [{
                label: 'Sales Amount',
                data: [
                    @foreach($formattedHourlySales as $data)
                        {{ $data["total"] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(59, 130, 246, 0.7)'
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
                        maxTicksLimit: 12,
                        maxRotation: 0
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
</script>
@endpush