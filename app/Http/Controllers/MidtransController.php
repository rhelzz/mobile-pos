<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY', '');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY', '');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false) === 'true';
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
    
    public function getSnapToken(Request $request)
    {
        // Tambahkan log untuk debugging
        Log::info('Midtrans Token Request', $request->all());

        // Validasi input
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        try {
            // Ambil data transaksi
            $transaction = Transaction::with('transactionItems.product')->findOrFail($request->transaction_id);

            // Persiapkan data untuk Midtrans
            $items = [];

            // Tambahkan item transaksi
            foreach ($transaction->transactionItems as $item) {
                $items[] = [
                    'id' => $item->product_id,
                    'price' => (int) $item->price,
                    'quantity' => $item->quantity,
                    'name' => substr($item->product->name, 0, 50), // Maksimal 50 karakter
                ];
            }

            // Cek apakah ada pajak dan diskon
            if ($transaction->tax_amount > 0) {
                $items[] = [
                    'id' => 'tax',
                    'price' => (int) $transaction->tax_amount,
                    'quantity' => 1,
                    'name' => 'Tax',
                ];
            }

            if ($transaction->discount_amount > 0) {
                $items[] = [
                    'id' => 'discount',
                    'price' => (int) -$transaction->discount_amount, // Negatif untuk diskon
                    'quantity' => 1,
                    'name' => 'Discount',
                ];
            }

            // Buat order_id unik dengan menambahkan timestamp untuk retries
            // Format: invoice_number-timestamp
            $uniqueOrderId = $transaction->invoice_number . '-' . time();

            // Data untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $uniqueOrderId, // Gunakan order_id yang unik
                    'gross_amount' => (int) $transaction->final_amount,
                ],
                'item_details' => $items,
                'customer_details' => [
                    'first_name' => $transaction->customer_name ?: 'Customer',
                    'email' => 'customer@example.com', // Ganti dengan email pelanggan jika tersedia
                    'phone' => '08123456789', // Ganti dengan nomor telepon pelanggan jika tersedia
                ],
            ];

            Log::info('Midtrans Params', $params);

            // Buat token transaksi
            $snapToken = Snap::getSnapToken($params);
            
            Log::info('Midtrans Token Generated', [
                'token' => $snapToken,
                'original_order_id' => $transaction->invoice_number,
                'new_order_id' => $uniqueOrderId
            ]);

            // Return token ke client
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate payment token: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();
            
            Log::info('Midtrans Notification Received', [
                'order_id' => $notification->order_id,
                'status_code' => $notification->status_code,
                'transaction_status' => $notification->transaction_status
            ]);
            
            $orderId = $notification->order_id;
            $statusCode = $notification->status_code;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $paymentType = $notification->payment_type;
            
            // Cari transaksi berdasarkan invoice number dari order_id
            // Untuk order_id yang telah dimodifikasi (ada "-" di dalamnya), ambil bagian pertama saja
            $originalInvoiceNumber = strpos($orderId, '-') !== false ? 
                strstr($orderId, '-', true) : $orderId;
                
            $transaction = Transaction::where('invoice_number', $originalInvoiceNumber)->first();
            
            if (!$transaction) {
                Log::error('Midtrans Notification: Transaction not found', [
                    'order_id' => $orderId,
                    'extracted_invoice' => $originalInvoiceNumber
                ]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Handling berbagai status pembayaran
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->midtrans_transaction_status = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $transaction->midtrans_transaction_status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->midtrans_transaction_status = 'success';
            } else if ($transactionStatus == 'pending') {
                $transaction->midtrans_transaction_status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $transaction->midtrans_transaction_status = 'deny';
            } else if ($transactionStatus == 'expire') {
                $transaction->midtrans_transaction_status = 'expire';
            } else if ($transactionStatus == 'cancel') {
                $transaction->midtrans_transaction_status = 'cancel';
            }

            // Simpan data Midtrans ke database
            $transaction->midtrans_transaction_id = $notification->transaction_id;
            $transaction->midtrans_payment_type = $paymentType;
            $transaction->midtrans_transaction_time = $notification->transaction_time;
            
            // Simpan data tambahan sesuai dengan metode pembayaran
            if ($paymentType == 'bank_transfer') {
                if (isset($notification->va_numbers[0]->va_number)) {
                    $transaction->midtrans_va_number = $notification->va_numbers[0]->va_number;
                }
            } else if ($paymentType == 'echannel') {
                $transaction->midtrans_payment_code = $notification->bill_key;
            } else if ($paymentType == 'cstore') {
                $transaction->midtrans_payment_code = $notification->payment_code;
            }
            
            // Simpan URL PDF instruksi pembayaran jika ada
            if (isset($notification->pdf_url)) {
                $transaction->midtrans_pdf_url = $notification->pdf_url;
            }
            
            $transaction->save();

            Log::info('Midtrans Notification: Transaction updated', [
                'order_id' => $orderId,
                'status' => $transaction->midtrans_transaction_status
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function success(Transaction $transaction)
    {
        // Update transaction status to success if it's not already
        if ($transaction->midtrans_transaction_status != 'success') {
            $transaction->midtrans_transaction_status = 'success';
            $transaction->save();
        }
        
        return view('transactions.success', compact('transaction'));
    }

    public function updateStatus(Transaction $transaction, Request $request)
    {
        // Validate status
        $request->validate([
            'status' => 'required|in:success,pending,cancel,expire,deny'
        ]);
        
        // Update transaction status
        $oldStatus = $transaction->midtrans_transaction_status;
        $transaction->midtrans_transaction_status = $request->status;
        $transaction->save();
        
        // Log the update
        Log::info('Transaction status updated manually', [
            'order_id' => $transaction->invoice_number,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'updated_by' => auth()->user()->name
        ]);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status transaksi berhasil diubah menjadi ' . $request->status
            ]);
        }
        
        return redirect()->back()->with('success', 'Status transaksi berhasil diubah menjadi ' . $request->status);
    }
}
