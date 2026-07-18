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
        Schema::create('titipan_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titipan_barang_id')->constrained()->cascadeOnDelete();
            $table->decimal('jumlah', 12, 3);
            $table->decimal('harga_jual_saat_itu', 12, 2);
            $table->decimal('komisi_saat_itu', 12, 2);
            $table->decimal('total_untuk_penitip', 12, 2);
            $table->decimal('total_komisi_toko', 12, 2);
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titipan_penjualans');
    }
};
