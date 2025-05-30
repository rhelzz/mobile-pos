<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class);
    
    // Categories Management
    Route::resource('categories', CategoryController::class);
    
    // Cashier Routes
    Route::get('/cashier', [TransactionController::class, 'create'])->name('cashier');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    
    // Transaction History
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    
    // Stock Management
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::post('/stock/add', [StockController::class, 'add'])->name('stock.add');
    Route::get('/stock/movements', [StockController::class, 'movements'])->name('stock.movements');
    // Tambahkan route ini di antara route stock lainnya
    Route::delete('stock/truncate', [StockController::class, 'truncateMovements'])->name('stock.truncate');
    
    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/hourly', [ReportController::class, 'hourly'])->name('reports.hourly');

    // Route untuk Midtrans
    Route::post('/midtrans/get-token', [MidtransController::class, 'getSnapToken'])->name('midtrans.token');
    Route::post('/midtrans/notification', [MidtransController::class, 'notification'])->name('midtrans.notification');
    Route::get('/transactions/{transaction}/payment', [TransactionController::class, 'paymentDetail'])->name('transactions.payment');
    // Add this inside your auth middleware group
    Route::get('/midtrans/success/{transaction}', [MidtransController::class, 'success'])->name('midtrans.success');
    Route::post('/transactions/{transaction}/update-status', [MidtransController::class, 'updateStatus'])->name('transactions.update-status');
    // Add this inside your auth middleware group
    Route::get('/pending-payments', [TransactionController::class, 'pendingPayments'])->name('transactions.pending');

});