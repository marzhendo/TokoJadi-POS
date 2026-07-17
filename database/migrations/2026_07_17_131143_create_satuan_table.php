<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: kg, liter, ikat, karung
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan');
    }
};
