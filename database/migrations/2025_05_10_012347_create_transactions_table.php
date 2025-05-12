<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->string('customer_name')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0); // Tambahkan kolom untuk menyimpan persentase diskon
            $table->decimal('final_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'other', 'midtrans'])->default('cash');
            $table->text('notes')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_transaction_status')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->string('midtrans_transaction_time')->nullable();
            $table->string('midtrans_payment_code')->nullable();
            $table->string('midtrans_pdf_url')->nullable();
            $table->string('midtrans_va_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
