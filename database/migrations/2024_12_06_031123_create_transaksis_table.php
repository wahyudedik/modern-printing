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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->json('data_pelanggan')->nullable();
            $table->json('data_produk')->nullable();
            $table->string('minimal_qty')->nullable();
            $table->string('total_qty')->nullable();
            $table->string('total_harga')->nullable();
            $table->enum('metode_pembayaran', ['transfer', 'cash', 'qris'])->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
