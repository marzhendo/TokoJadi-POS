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

        $produk = Produk::create([
            'nama'                        => $data['nama'],
            'kategori_id'                 => $data['kategori_id'],
            'satuan_dasar_id'             => $data['satuan_dasar_id'],
            'stok_saat_ini'               => $data['stok_saat_ini'] ?? 0,
            'stok_minimum'                => $data['stok_minimum'] ?? 0,
            'harga_modal_per_satuan_dasar' => $data['harga_modal_per_satuan_dasar'] ?? 0,
        ]);

        // Simpan satuan jual (minimal 1 — satuan dasar itu sendiri)
        if (!empty($data['satuan_jual'])) {
            foreach ($data['satuan_jual'] as $sj) {
                $produk->satuanJual()->create([
                    'satuan_id'                 => $sj['satuan_id'],
                    'jumlah_dalam_satuan_dasar' => $sj['jumlah_dalam_satuan_dasar'],
                    'harga_jual'                => $sj['harga_jual'],
                ]);
            }
        }

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
        $produk->load('satuanJual');
        return view('produk.edit', compact('produk', 'kategoriList', 'satuanList'));
    }

    public function update(StoreProdukRequest $request, Produk $produk): RedirectResponse
    {
        // ponytail: update di-defer ke modul edit — belum ada view-nya
        return redirect()->route('produk.index');
    }

    public function destroy(Produk $produk): RedirectResponse
    {
        $nama = $produk->nama;
        $produk->delete();
        return redirect()->route('produk.index')
            ->with('success', "Produk \"{$nama}\" dihapus.");
    }
}
