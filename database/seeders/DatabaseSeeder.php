<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Engkong',
            'email'    => 'engkong@tokojadi.test',
            'password' => bcrypt('tokojadi123'),
        ]);

        // Seed referensi
        $sayur  = Kategori::create(['nama' => 'Sayur']);
        $buah   = Kategori::create(['nama' => 'Buah']);
        $beras  = Kategori::create(['nama' => 'Beras & Bijian']);
        $bumbu  = Kategori::create(['nama' => 'Bumbu']);
        $minyak = Kategori::create(['nama' => 'Minyak & Lemak']);

        $kg     = Satuan::create(['nama' => 'kg']);
        $liter  = Satuan::create(['nama' => 'liter']);
        $ikat   = Satuan::create(['nama' => 'ikat']);
        $karung = Satuan::create(['nama' => 'karung']);
        $buah_s = Satuan::create(['nama' => 'buah']);
        $jerigen = Satuan::create(['nama' => 'jerigen']);

        // Produk contoh
        $sawi = Produk::create([
            'nama'                         => 'Sawi Hijau (Caisim)',
            'kategori_id'                  => $sayur->id,
            'satuan_dasar_id'              => $ikat->id,
            'stok_saat_ini'                => 45,
            'stok_minimum'                 => 10,
            'harga_modal_per_satuan_dasar' => 4500,
        ]);
        $sawi->satuanJual()->create([
            'satuan_id' => $ikat->id, 'jumlah_dalam_satuan_dasar' => 1, 'harga_jual' => 6000,
        ]);

        $bawang = Produk::create([
            'nama'                         => 'Bawang Merah Brebes',
            'kategori_id'                  => $bumbu->id,
            'satuan_dasar_id'              => $kg->id,
            'stok_saat_ini'                => 8,
            'stok_minimum'                 => 10,  // menipis
            'harga_modal_per_satuan_dasar' => 28000,
        ]);
        $bawang->satuanJual()->create([
            'satuan_id' => $kg->id, 'jumlah_dalam_satuan_dasar' => 1, 'harga_jual' => 35000,
        ]);

        $beras_pw = Produk::create([
            'nama'                         => 'Beras Pandan Wangi',
            'kategori_id'                  => $beras->id,
            'satuan_dasar_id'              => $kg->id,
            'stok_saat_ini'                => 0,   // habis
            'stok_minimum'                 => 50,
            'harga_modal_per_satuan_dasar' => 12400,
        ]);
        $beras_pw->satuanJual()->createMany([
            ['satuan_id' => $kg->id,     'jumlah_dalam_satuan_dasar' => 1,  'harga_jual' => 14000],
            ['satuan_id' => $karung->id, 'jumlah_dalam_satuan_dasar' => 25, 'harga_jual' => 340000],
        ]);

        $minyak_g = Produk::create([
            'nama'                         => 'Minyak Goreng Tropical',
            'kategori_id'                  => $minyak->id,
            'satuan_dasar_id'              => $liter->id,
            'stok_saat_ini'                => 120,
            'stok_minimum'                 => 20,
            'harga_modal_per_satuan_dasar' => 15500,
        ]);
        $minyak_g->satuanJual()->createMany([
            ['satuan_id' => $liter->id,   'jumlah_dalam_satuan_dasar' => 1,  'harga_jual' => 17500],
            ['satuan_id' => $jerigen->id, 'jumlah_dalam_satuan_dasar' => 18, 'harga_jual' => 310000],
        ]);
    }
}
