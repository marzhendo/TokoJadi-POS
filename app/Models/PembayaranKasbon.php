<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranKasbon extends Model
{
    protected $fillable = [
        'transaksi_id',
        'jumlah_bayar',
        'tanggal_bayar',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }
}
