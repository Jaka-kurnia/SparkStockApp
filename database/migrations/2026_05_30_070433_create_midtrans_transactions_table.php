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
        Schema::create('midtrans_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->string('order_id')->unique(); // ID unik transaksi di server Midtrans
            $table->string('snap_token')->nullable(); // Token untuk memicu pop-up pembayaran di sisi client
            $table->string('transaction_id')->nullable(); // ID resmi transaksi dari Midtrans setelah diproses
            $table->string('payment_type', 50)->nullable();
            $table->decimal('gross_amount', 12, 2);
            $table->string('transaction_status', 30);
            $table->string('fraud_status', 20)->nullable();
            $table->string('payment_code', 100)->nullable();
            $table->timestamp('expiry_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->text('response_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midtrans_transactions');
    }
};
