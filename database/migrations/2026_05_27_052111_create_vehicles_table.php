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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->char('plate_number', 10)->unique();
            $table->enum('type', ['Matic', 'Bebek', 'Sport', 'Adventure', 'Klasik', 'Listrik']);
            $table->integer('year');
            $table->string('brand');
            $table->string('merk');
            $table->string('color');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
