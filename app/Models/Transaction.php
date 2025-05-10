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
        'total_amount',
        'tax_amount',
        'discount_amount',
        'final_amount',
        'payment_method',
        'notes',
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
