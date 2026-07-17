<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'satuan_dasar_id',
        'nama',
        'stok_saat_ini',
        'stok_minimum',
        'harga_modal_per_satuan_dasar',
    ];

    // SKILL: laravel-model — cast kolom uang/stok
    protected $casts = [
        'stok_saat_ini'               => 'decimal:3',
        'stok_minimum'                => 'decimal:3',
        'harga_modal_per_satuan_dasar' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuanDasar(): BelongsTo
    {
        // FK: satuan_dasar_id → satuan
        return $this->belongsTo(Satuan::class, 'satuan_dasar_id');
    }

    public function satuanJual(): HasMany
    {
        return $this->hasMany(ProdukSatuanJual::class);
    }

    // Helper: status stok untuk badge di view
    // ponytail: method ini display-only, bukan logic bisnis — aman di model
    public function statusStok(): string
    {
        if ($this->stok_saat_ini <= 0) {
            return 'habis';
        }
        if ($this->stok_saat_ini <= $this->stok_minimum) {
            return 'menipis';
        }
        return 'tersedia';
    }
}
