<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Produk;
use App\Models\ProdukSatuanJual;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TransaksiController extends Controller
{
    public function index(): View
    {
        $transaksi = Transaksi::query()
            ->when(request('dari'), fn($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->when(request('sampai'), fn($q, $v) => $q->whereDate('tanggal', '<=', $v))
            ->with('detail.produkSatuanJual.produk')
            ->latest('tanggal')
            ->paginate(20);

        return view('transaksi.index', compact('transaksi'));
    }

    public function create(): View
    {
        // Load all active products with their active satuan_jual to populate JS cart
        $produkList = Produk::with(['satuanDasar', 'satuanJual' => fn($q) => $q->where('aktif', true)])
            ->where('aktif', true)
            ->get();
        // Load all pelanggan to populate dropdown
        $pelangganList = \App\Models\Pelanggan::orderBy('nama')->get();
        return view('transaksi.create', compact('produkList', 'pelangganList'));
    }

    public function store(StoreTransaksiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // 1. Validasi stok awal (Quick Check)
        $itemsByProduk = [];
        foreach ($data['items'] as $item) {
            $sj = ProdukSatuanJual::with('produk.satuanDasar')->findOrFail($item['satuan_jual_id']);
            $produk = $sj->produk;
            $konversi = $sj->jumlah_dalam_satuan_dasar;
            $qtyDasar = $item['jumlah'] * $konversi;

            if (!isset($itemsByProduk[$produk->id])) {
                $itemsByProduk[$produk->id] = 0;
            }
            $itemsByProduk[$produk->id] += $qtyDasar;

            if ($produk->stok_saat_ini < $itemsByProduk[$produk->id]) {
                throw ValidationException::withMessages([
                    'items' => "Stok {$produk->nama} tidak cukup (tersedia " . floatval($produk->stok_saat_ini) . " {$produk->satuanDasar->nama}, diminta " . floatval($itemsByProduk[$produk->id]) . " {$produk->satuanDasar->nama})."
                ]);
            }
        }

        // 2. DB Transaction with Lock
        DB::transaction(function () use ($data) {
            $transaksi = Transaksi::create([
                'tanggal' => now(),
                'metode_bayar' => $data['metode_bayar'],
                'pelanggan_id' => $data['metode_bayar'] === 'kasbon' ? $data['pelanggan_id'] : null,
                'status_kasbon' => $data['metode_bayar'] === 'kasbon' ? 'belum_lunas' : null,
                'total_belanja' => 0,
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $sj = ProdukSatuanJual::findOrFail($item['satuan_jual_id']);
                
                // Re-fetch product with lock to prevent race conditions
                $produk = Produk::with('satuanDasar')->where('id', $sj->produk_id)->lockForUpdate()->first();
                $qtyDasar = $item['jumlah'] * $sj->jumlah_dalam_satuan_dasar;
                
                if ($produk->stok_saat_ini < $qtyDasar) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$produk->nama} tidak cukup saat transaksi diproses (tersedia " . floatval($produk->stok_saat_ini) . " {$produk->satuanDasar->nama}, diminta " . floatval($qtyDasar) . " {$produk->satuanDasar->nama})."
                    ]);
                }
                
                $subtotal = $item['jumlah'] * $sj->harga_jual;
                
                $transaksi->detail()->create([
                    'produk_id' => $produk->id,
                    'satuan_jual_id' => $sj->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $sj->harga_jual, // Truth from DB
                    'subtotal' => $subtotal,
                ]);
                
                // Decrement stock
                $produk->decrement('stok_saat_ini', $qtyDasar);
                $total += $subtotal;
            }
            
            $transaksi->update(['total_belanja' => $total]);
        });

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }
}
