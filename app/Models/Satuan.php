<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Satuan extends Model
{
    protected $table = 'satuan';

    protected $fillable = ['nama'];

    public function produkSatuanJual(): HasMany
    {
        return $this->hasMany(ProdukSatuanJual::class);
    }
}
