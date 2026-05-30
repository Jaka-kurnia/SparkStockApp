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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            $table->foreignId('mechanic_id')->constrained('mechanics')->restrictOnDelete();
            $table->char('kode_order', 25)->unique();
            $table->char('kode_queue', 5)->unique();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'paid', 'closed'])->default('pending');
            $table->text('keluhan')->nullable();
            $table->date('service_date');
            $table->decimal('total_service', 12, 2)->default(0);
            $table->decimal('total_part', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'debit', 'credit', 'midtrans'])->default('cash');
            $table->enum('payment_status', ['unpaid', 'paid', ''])->default('unpaid');
            $table->enum('midtrans_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
