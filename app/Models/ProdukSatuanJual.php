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
        'aktif',
    ];

    // SKILL: laravel-model — cast decimal & boolean
    protected $casts = [
        'jumlah_dalam_satuan_dasar' => 'decimal:3',
        'harga_jual'                => 'decimal:2',
        'aktif'                     => 'boolean',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }

    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class, 'satuan_jual_id');
    }

    /**
     * Mengecek apakah satuan jual ini pernah dipakai di transaksi
     */
    public function isUsedInTransaction(): bool
    {
        return $this->transaksiDetail()->exists();
    }
}
