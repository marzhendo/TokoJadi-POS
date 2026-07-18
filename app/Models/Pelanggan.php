<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $fillable = ['nama', 'kontak', 'alamat'];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'pelanggan_id');
    }
}
