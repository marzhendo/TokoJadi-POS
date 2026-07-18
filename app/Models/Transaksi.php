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
        'status_kasbon',
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
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function pembayaranKasbon(): HasMany
    {
        return $this->hasMany(PembayaranKasbon::class);
    }

    public function sisaKasbon(): float
    {
        // Calculate remaining debt based on loaded relation if possible, or query otherwise
        $terbayar = $this->relationLoaded('pembayaranKasbon') 
            ? $this->pembayaranKasbon->sum('jumlah_bayar')
            : $this->pembayaranKasbon()->sum('jumlah_bayar');
            
        return max(0, $this->total_belanja - $terbayar);
    }

    public function isLunas(): bool
    {
        return $this->sisaKasbon() <= 0;
    }
}
