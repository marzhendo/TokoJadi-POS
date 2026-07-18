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
        Schema::create('pembayaran_kasbons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->cascadeOnDelete();
            $table->decimal('jumlah_bayar', 12, 2);
            $table->date('tanggal_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_kasbons');
    }
};
