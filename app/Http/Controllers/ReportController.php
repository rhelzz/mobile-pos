<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : date('Y-m-d', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');
        
        // Daily sales
        $dailySales = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(final_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Payment method breakdown
        $paymentMethods = Transaction::select(
                'payment_method',
                DB::raw('SUM(final_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('payment_method')
            ->get();
        
        // Summary stats
        $summary = [
            'total_sales' => Transaction::whereDate('created_at', '>=', $startDate)
                           ->whereDate('created_at', '<=', $endDate)
                           ->sum('final_amount'),
            'total_transactions' => Transaction::whereDate('created_at', '>=', $startDate)
                                 ->whereDate('created_at', '<=', $endDate)
                                 ->count(),
            'avg_sale' => Transaction::whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate)
                        ->avg('final_amount') ?? 0,
        ];
        
        return view('reports.sales', compact('dailySales', 'paymentMethods', 'summary', 'startDate', 'endDate'));
    }

    public function products(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : date('Y-m-d', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');
        
        // Most popular products
        $popularProducts = TransactionItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_sales')
            )
            ->with('product')
            ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();
        
        // Most profitable products
        $profitableProducts = TransactionItem::select(
                'product_id',
                DB::raw('SUM(subtotal) as total_sales'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->with('product')
            ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->take(10)
            ->get();
        
        return view('reports.products', compact('popularProducts', 'profitableProducts', 'startDate', 'endDate'));
    }

    public function hourly(Request $request)
    {
        $startDate = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : date('Y-m-d', strtotime('-30 days'));
        $endDate = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : date('Y-m-d');
        
        // Hourly sales analysis
        $hourlySales = Transaction::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(final_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        // Format hours for display (24-hour format)
        $formattedHourlySales = [];
        foreach (range(0, 23) as $hour) {
            $hourData = $hourlySales->firstWhere('hour', $hour);
            $formattedHourlySales[$hour] = [
                'hour' => sprintf('%02d:00', $hour),
                'total' => $hourData ? $hourData->total : 0,
                'count' => $hourData ? $hourData->count : 0,
            ];
        }
        
        // Find busiest hour
        $busiestHour = $hourlySales->sortByDesc('count')->first();
        $mostProfitableHour = $hourlySales->sortByDesc('total')->first();
        
        return view('reports.hourly', compact(
            'formattedHourlySales',
            'busiestHour',
            'mostProfitableHour',
            'startDate',
            'endDate'
        ));
    }
}
