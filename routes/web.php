<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('kategori', App\Http\Controllers\KategoriController::class);
    Route::resource('satuan', App\Http\Controllers\SatuanController::class);
    Route::resource('produk', App\Http\Controllers\ProdukController::class);
    Route::patch('produk/{produk}/toggle-aktif', [App\Http\Controllers\ProdukController::class, 'toggleAktif'])->name('produk.toggle-aktif');
    Route::resource('transaksi', App\Http\Controllers\TransaksiController::class);
    
    // Laporan
    Route::get('/laporan/penjualan', [App\Http\Controllers\LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    Route::get('/laporan/stok-menipis', [App\Http\Controllers\LaporanController::class, 'stokMenipis'])->name('laporan.stok-menipis');
    Route::get('/laporan/produk-terlaris', [App\Http\Controllers\LaporanController::class, 'produkTerlaris'])->name('laporan.produk-terlaris');
    Route::get('/laporan/untung-rugi', [App\Http\Controllers\LaporanController::class, 'untungRugi'])->name('laporan.untung-rugi');
    
    // Titipan
    Route::get('/titipan/riwayat', [App\Http\Controllers\TitipanController::class, 'riwayat'])->name('titipan.riwayat');
    Route::resource('titipan', App\Http\Controllers\TitipanController::class)->only(['index', 'create', 'store']);
    Route::post('/titipan/{titipan}/jual', [App\Http\Controllers\TitipanController::class, 'catatPenjualan'])->name('titipan.jual');
    
    // Pelanggan & Kasbon
    Route::resource('pelanggan', App\Http\Controllers\PelangganController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/kasbon', [App\Http\Controllers\KasbonController::class, 'index'])->name('kasbon.index');
    Route::post('/kasbon/{transaksi}/bayar', [App\Http\Controllers\KasbonController::class, 'bayar'])->name('kasbon.bayar');
});

require __DIR__.'/auth.php';
