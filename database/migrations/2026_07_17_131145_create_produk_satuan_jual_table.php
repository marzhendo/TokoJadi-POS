<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk_satuan_jual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->cascadeOnDelete();
            $table->foreignId('satuan_id')->constrained('satuan')->cascadeOnDelete();
            // jumlah satuan jual ini = berapa satuan dasar
            // contoh: 1 karung = 25 kg → jumlah_dalam_satuan_dasar = 25
            $table->decimal('jumlah_dalam_satuan_dasar', 12, 3);
            $table->decimal('harga_jual', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk_satuan_jual');
    }
};
