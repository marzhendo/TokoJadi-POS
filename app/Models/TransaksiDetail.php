<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiDetail extends Model
{
    protected $table = 'transaksi_detail';

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'satuan_jual_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'jumlah' => 'decimal:3',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function produkSatuanJual(): BelongsTo
    {
        return $this->belongsTo(ProdukSatuanJual::class, 'satuan_jual_id');
    }
}
