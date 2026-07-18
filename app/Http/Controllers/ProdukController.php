<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdukRequest;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProdukController extends Controller
{
    // SKILL: laravel-controller — index support filter query string
    public function index(): View
    {
        $produk = Produk::query()
            ->with(['kategori', 'satuanDasar', 'satuanJual'])
            ->when(request('search'), fn ($q, $v) => $q->where('nama', 'like', "%{$v}%"))
            ->when(request('kategori_id'), fn ($q, $v) => $q->where('kategori_id', $v))
            ->when(request('status'), function ($q, $v) {
                match ($v) {
                    'tersedia' => $q->where('stok_saat_ini', '>', 0)
                                    ->whereColumn('stok_saat_ini', '>', 'stok_minimum'),
                    'menipis'  => $q->where('stok_saat_ini', '>', 0)
                                    ->whereColumn('stok_saat_ini', '<=', 'stok_minimum'),
                    'habis'    => $q->where('stok_saat_ini', '<=', 0),
                    default    => null,
                };
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $kategoriList = Kategori::orderBy('nama')->get();

        return view('produk.index', compact('produk', 'kategoriList'));
    }

    public function create(): View
    {
        $kategoriList = Kategori::orderBy('nama')->get();
        $satuanList   = Satuan::orderBy('nama')->get();

        return view('produk.create', compact('kategoriList', 'satuanList'));
    }

    // SKILL: laravel-controller — WAJIB pakai Form Request untuk validasi
    public function store(StoreProdukRequest $request): RedirectResponse
    {
        $data = $request->validated();

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, &$produk) {
            $produk = Produk::create([
                'nama'                        => $data['nama'],
                'kategori_id'                 => $data['kategori_id'],
                'satuan_dasar_id'             => $data['satuan_dasar_id'],
                'stok_saat_ini'               => $data['stok_saat_ini'] ?? 0,
                'stok_minimum'                => $data['stok_minimum'] ?? 0,
                'harga_modal_per_satuan_dasar' => $data['harga_modal_per_satuan_dasar'] ?? 0,
            ]);

            // Simpan satuan jual (minimal 1 — divalidasi oleh Request)
            foreach ($data['satuan_jual'] as $sj) {
                $produk->satuanJual()->create([
                    'satuan_id'                 => $sj['satuan_id'],
                    'jumlah_dalam_satuan_dasar' => $sj['jumlah_dalam_satuan_dasar'],
                    'harga_jual'                => $sj['harga_jual'],
                ]);
            }
        });

        return redirect()->route('produk.index')
            ->with('success', "Produk \"{$produk->nama}\" berhasil ditambahkan.");
    }

    // show, edit, update, destroy — disiapkan, diisi saat modul kasir butuh
    public function show(Produk $produk): View
    {
        $produk->load(['kategori', 'satuanDasar', 'satuanJual.satuan']);
        return view('produk.show', compact('produk'));
    }

    public function edit(Produk $produk): View
    {
        $kategoriList = Kategori::orderBy('nama')->get();
        $satuanList   = Satuan::orderBy('nama')->get();
        $produk->load(['satuanJual' => fn($q) => $q->where('aktif', true)]);
        return view('produk.edit', compact('produk', 'kategoriList', 'satuanList'));
    }

    public function update(\App\Http\Requests\UpdateProdukRequest $request, Produk $produk): RedirectResponse
    {
        $data = $request->validated();
        
        // Strip out stok_saat_ini jika ada, karena form edit tidak mengirimkan stok, dan stok tidak boleh berubah di sini.
        unset($data['stok_saat_ini']);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $produk) {
            $produk->update([
                'nama'                        => $data['nama'],
                'kategori_id'                 => $data['kategori_id'],
                'satuan_dasar_id'             => $data['satuan_dasar_id'],
                'stok_minimum'                => $data['stok_minimum'] ?? 0,
                'harga_modal_per_satuan_dasar' => $data['harga_modal_per_satuan_dasar'] ?? 0,
            ]);

            // Identifikasi id satuan_jual yang dikirim dari form
            $incomingIds = collect($data['satuan_jual'])->pluck('id')->filter()->toArray();

            // Tangani penghapusan (hard delete atau nonaktifkan)
            $existingSatuanJual = $produk->satuanJual;
            foreach ($existingSatuanJual as $esj) {
                if (!in_array($esj->id, $incomingIds)) {
                    if ($esj->isUsedInTransaction()) {
                        // Jika sudah pernah ada transaksi, jangan dihapus tapi dinonaktifkan
                        $esj->update(['aktif' => false]);
                    } else {
                        // Jika belum ada transaksi, aman untuk di hard delete
                        $esj->delete();
                    }
                }
            }

            // Tambah baru atau perbarui yang sudah ada
            foreach ($data['satuan_jual'] as $sj) {
                if (!empty($sj['id'])) {
                    // Update yang sudah ada
                    $produk->satuanJual()->where('id', $sj['id'])->update([
                        'satuan_id'                 => $sj['satuan_id'],
                        'jumlah_dalam_satuan_dasar' => $sj['jumlah_dalam_satuan_dasar'],
                        'harga_jual'                => $sj['harga_jual'],
                        'aktif'                     => true, // Pastikan aktif
                    ]);
                } else {
                    // Buat baru
                    $produk->satuanJual()->create([
                        'satuan_id'                 => $sj['satuan_id'],
                        'jumlah_dalam_satuan_dasar' => $sj['jumlah_dalam_satuan_dasar'],
                        'harga_jual'                => $sj['harga_jual'],
                        'aktif'                     => true,
                    ]);
                }
            }
        });

        return redirect()->route('produk.index')
            ->with('success', "Produk \"{$produk->nama}\" berhasil diubah.");
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $nama = $produk->nama;

        if ($produk->isUsedInTransaction()) {
            $produk->update(['aktif' => false]);
            return redirect()->route('produk.index')
                ->with('success', "Produk \"{$nama}\" pernah terjual, dinonaktifkan agar riwayat transaksi tetap valid.");
        }

        // Hard delete
        $produk->satuanJual()->delete();
        $produk->delete();
        
        return redirect()->route('produk.index')
            ->with('success', "Produk \"{$nama}\" dihapus permanen.");
    }

    public function toggleAktif(Produk $produk): RedirectResponse
    {
        $produk->update(['aktif' => true]);
        return redirect()->route('produk.index')
            ->with('success', "Produk \"{$produk->nama}\" diaktifkan kembali.");
    }
}
