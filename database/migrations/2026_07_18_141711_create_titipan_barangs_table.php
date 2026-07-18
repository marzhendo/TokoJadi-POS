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
        Schema::create('titipan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penitip');
            $table->string('nama_barang');
            $table->decimal('harga_jual', 12, 2);
            $table->decimal('komisi_toko', 12, 2);
            $table->decimal('jumlah_dititipkan', 12, 3);
            $table->decimal('jumlah_terjual', 12, 3)->default(0);
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titipan_barangs');
    }
};
