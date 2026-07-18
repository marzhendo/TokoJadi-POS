<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitipanBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_penitip',
        'nama_barang',
        'harga_jual',
        'komisi_toko',
        'jumlah_dititipkan',
        'jumlah_terjual',
        'status',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'komisi_toko' => 'decimal:2',
        'jumlah_dititipkan' => 'decimal:3',
        'jumlah_terjual' => 'decimal:3',
    ];

    public function penjualan()
    {
        return $this->hasMany(TitipanPenjualan::class, 'titipan_barang_id');
    }

    public function sisaStok(): float
    {
        return (float) ($this->jumlah_dititipkan - $this->jumlah_terjual);
    }
}
