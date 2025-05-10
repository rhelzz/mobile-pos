<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Count total products
        $totalProducts = Product::count();
        
        // Count total categories
        $totalCategories = Category::count();
        
        // Get today's sales
        $todaySales = Transaction::whereDate('created_at', today())->sum('final_amount');
        
        // Get total sales
        $totalSales = Transaction::sum('final_amount');
        
        // Get products with low stock (less than 10)
        $lowStockProducts = Product::where('stock', '<', 10)->get();
        
        // Recent transactions (latest 5)
        $recentTransactions = Transaction::with('user', 'transactionItems.product')
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get();
        
        // Sales data for the last 7 days for chart
        $salesData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(final_amount) as total')
            )
            ->whereDate('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
        
        // Format dates and fill in missing dates
        $formattedSalesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $formattedSalesData[$date] = $salesData[$date] ?? 0;
        }
        
        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'todaySales',
            'totalSales',
            'lowStockProducts',
            'recentTransactions',
            'formattedSalesData'
        ));
    }
}
