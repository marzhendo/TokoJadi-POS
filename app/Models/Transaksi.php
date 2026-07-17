<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    
    protected $fillable = [
        'tanggal',
        'metode_bayar',
        'pelanggan_id',
        'total_belanja',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total_belanja' => 'decimal:2',
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class); // model Pelanggan doesn't exist yet but relation is ready for v2
    }
}
