<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_name',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'discount_percent',
        'final_amount',
        'payment_method',
        'notes',
        // Tambahkan field untuk Midtrans
        'midtrans_transaction_id',
        'midtrans_transaction_status',
        'midtrans_payment_type',
        'midtrans_transaction_time',
        'midtrans_payment_code',
        'midtrans_pdf_url',
        'midtrans_va_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
