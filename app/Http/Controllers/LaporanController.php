<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function penjualan(Request $request): View
    {
        // Default filter: awal bulan hingga hari ini
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', Carbon::now()->toDateString());

        // Pastikan input kembali ke request object agar pagination links dan form value sesuai
        $request->merge(['dari' => $dari, 'sampai' => $sampai]);

        $query = Transaksi::query()
            ->when($request->dari, fn ($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->when($request->sampai, fn ($q, $v) => $q->whereDate('tanggal', '<=', $v));

        $totalTransaksi = (clone $query)->count();
        $totalOmzet = (clone $query)->sum('total_belanja');
        
        $rataRata = $totalTransaksi > 0 ? $totalOmzet / $totalTransaksi : 0;

        $transaksi = (clone $query)
            ->with('detail') // Eager load detail untuk hitung jumlah item
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('laporan.penjualan', compact('transaksi', 'totalTransaksi', 'totalOmzet', 'rataRata'));
    }

    public function stokMenipis(): View
    {
        $produk = \App\Models\Produk::with('satuanDasar')
            ->where('aktif', true)
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            // Urutkan berdasarkan selisih terkecil antara stok_saat_ini dan stok_minimum
            ->orderByRaw('(stok_saat_ini - stok_minimum) ASC')
            ->paginate(20);

        return view('laporan.stok-menipis', compact('produk'));
    }

    public function produkTerlaris(Request $request): View
    {
        // Default filter: awal bulan hingga hari ini
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', Carbon::now()->toDateString());

        $request->merge(['dari' => $dari, 'sampai' => $sampai]);

        $produkTerlaris = \Illuminate\Support\Facades\DB::table('transaksi_detail')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
            ->join('produk_satuan_jual', 'produk_satuan_jual.id', '=', 'transaksi_detail.satuan_jual_id')
            ->join('produk', 'produk.id', '=', 'transaksi_detail.produk_id')
            ->join('satuan', 'satuan.id', '=', 'produk.satuan_dasar_id')
            ->leftJoin('kategori', 'kategori.id', '=', 'produk.kategori_id')
            ->when($request->dari, fn ($q, $v) => $q->whereDate('transaksi.tanggal', '>=', $v))
            ->when($request->sampai, fn ($q, $v) => $q->whereDate('transaksi.tanggal', '<=', $v))
            ->selectRaw('
                produk.id as produk_id,
                produk.nama as produk_nama, 
                kategori.nama as kategori_nama, 
                satuan.nama as satuan_dasar_nama,
                SUM(transaksi_detail.jumlah * produk_satuan_jual.jumlah_dalam_satuan_dasar) as total_terjual_satuan_dasar,
                SUM(transaksi_detail.subtotal) as total_omzet
            ')
            ->groupBy('produk.id', 'produk.nama', 'kategori.nama', 'satuan.nama')
            ->orderByDesc('total_terjual_satuan_dasar')
            ->limit(10)
            ->get();

        return view('laporan.produk-terlaris', compact('produkTerlaris'));
    }

    public function untungRugi(Request $request): View
    {
        // Default filter: awal bulan hingga hari ini
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', Carbon::now()->toDateString());

        $request->merge(['dari' => $dari, 'sampai' => $sampai]);

        $baseQuery = \Illuminate\Support\Facades\DB::table('transaksi_detail')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
            ->join('produk_satuan_jual', 'produk_satuan_jual.id', '=', 'transaksi_detail.satuan_jual_id')
            ->join('produk', 'produk.id', '=', 'transaksi_detail.produk_id')
            ->when($request->dari, fn ($q, $v) => $q->whereDate('transaksi.tanggal', '>=', $v))
            ->when($request->sampai, fn ($q, $v) => $q->whereDate('transaksi.tanggal', '<=', $v));

        // 1. Query Ringkasan (Total Level)
        $ringkasan = (clone $baseQuery)
            ->selectRaw('
                SUM(transaksi_detail.subtotal) as total_omzet,
                SUM(produk.harga_modal_per_satuan_dasar * produk_satuan_jual.jumlah_dalam_satuan_dasar * transaksi_detail.jumlah) as total_modal
            ')
            ->first();

        $totalOmzet = $ringkasan->total_omzet ?? 0;
        $totalModal = $ringkasan->total_modal ?? 0;
        $totalUntung = $totalOmzet - $totalModal;
        $marginPersen = $totalOmzet > 0 ? ($totalUntung / $totalOmzet) * 100 : 0;

        // 2. Query Breakdown per Produk
        $breakdown = (clone $baseQuery)
            ->selectRaw('
                produk.id as produk_id,
                produk.nama as produk_nama,
                SUM(transaksi_detail.subtotal) as total_omzet,
                SUM(produk.harga_modal_per_satuan_dasar * produk_satuan_jual.jumlah_dalam_satuan_dasar * transaksi_detail.jumlah) as total_modal,
                (SUM(transaksi_detail.subtotal) - SUM(produk.harga_modal_per_satuan_dasar * produk_satuan_jual.jumlah_dalam_satuan_dasar * transaksi_detail.jumlah)) as untung_bersih
            ')
            ->groupBy('produk.id', 'produk.nama')
            ->orderByRaw('untung_bersih DESC')
            ->paginate(20)
            ->withQueryString();

        return view('laporan.untung-rugi', compact('breakdown', 'totalOmzet', 'totalModal', 'totalUntung', 'marginPersen'));
    }
}
