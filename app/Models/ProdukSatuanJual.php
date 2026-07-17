<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukSatuanJual extends Model
{
    protected $table = 'produk_satuan_jual';

    protected $fillable = [
        'produk_id',
        'satuan_id',
        'jumlah_dalam_satuan_dasar',
        'harga_jual',
    ];

    // SKILL: laravel-model — cast decimal
    protected $casts = [
        'jumlah_dalam_satuan_dasar' => 'decimal:3',
        'harga_jual'                => 'decimal:2',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }
}
