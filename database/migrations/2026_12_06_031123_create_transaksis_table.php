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
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->decimal('total_harga', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled', 'quality_check', 'processing'])->default('pending');
            $table->string('payment_method');
            $table->string('estimasi_selesai');
            $table->date('tanggal_dibuat');
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
        });

        Schema::create('transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('transaksi_id')->constrained('transaksis')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('produks')->cascadeOnDelete();
            $table->foreignId('bahan_id')->constrained('bahans')->cascadeOnDelete();
            $table->integer('kuantitas');
            $table->decimal('harga_satuan', 10, 2);
            $table->json('spesifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
        Schema::dropIfExists('transaksi_items');
    }
};
