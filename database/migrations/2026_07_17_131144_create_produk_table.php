<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignId('satuan_dasar_id')->constrained('satuan')->cascadeOnDelete();
            $table->string('nama');
            // SKILL: laravel-migration — stok & harga WAJIB decimal, bukan float
            $table->decimal('stok_saat_ini', 12, 3)->default(0);    // 3 desimal: liter/kg
            $table->decimal('stok_minimum', 12, 3)->default(0);
            $table->decimal('harga_modal_per_satuan_dasar', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
