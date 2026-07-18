<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitipanPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'titipan_barang_id',
        'jumlah',
        'harga_jual_saat_itu',
        'komisi_saat_itu',
        'total_untuk_penitip',
        'total_komisi_toko',
        'tanggal',
    ];

    protected $casts = [
        'jumlah' => 'decimal:3',
        'harga_jual_saat_itu' => 'decimal:2',
        'komisi_saat_itu' => 'decimal:2',
        'total_untuk_penitip' => 'decimal:2',
        'total_komisi_toko' => 'decimal:2',
        'tanggal' => 'date',
    ];

    public function titipanBarang()
    {
        return $this->belongsTo(TitipanBarang::class, 'titipan_barang_id');
    }
}
