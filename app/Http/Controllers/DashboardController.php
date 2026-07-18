<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Produk Aktif
        $totalProdukAktif = Produk::where('aktif', true)->count();

        // 2. Margin Tipis (< 20%)
        // Hitung selisih untung / omzet * 100
        $produkAktif = Produk::with(['satuanDasar', 'satuanJual.satuan'])->where('aktif', true)->get();
        
        $produkMarginTipis = collect();
        foreach ($produkAktif as $p) {
            foreach ($p->satuanJual as $sj) {
                if (!$sj->aktif) continue;
                
                $modalTotal = $p->harga_modal_per_satuan_dasar * $sj->jumlah_dalam_satuan_dasar;
                $hargaJual = $sj->harga_jual;
                if ($hargaJual > 0) {
                    $marginPersen = (($hargaJual - $modalTotal) / $hargaJual) * 100;
                    if ($marginPersen < 20) {
                        $p->margin_kritis = round($marginPersen, 1);
                        $p->satuan_kritis = $sj->satuan->nama ?? 'Unit';
                        $p->is_margin_tipis = true;
                        $produkMarginTipis->push($p);
                        break; 
                    }
                }
            }
        }
        $countMarginTipis = $produkMarginTipis->count();

        // 3. Belum Update (>3 hari)
        $produkBelumUpdate = Produk::where('aktif', true)
            ->where('updated_at', '<=', now()->subDays(3))
            ->get();
            
        $countBelumUpdate = $produkBelumUpdate->count();

        foreach ($produkBelumUpdate as $p) {
            $p->is_belum_update = true;
        }

        // 4. Produk Terlaris Minggu Ini
        $tujuhHariLalu = now()->subDays(7)->toDateString();
        
        $produkTerlaris = DB::table('transaksi_detail')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_detail.transaksi_id')
            ->join('produk_satuan_jual', 'produk_satuan_jual.id', '=', 'transaksi_detail.satuan_jual_id')
            ->join('produk', 'produk.id', '=', 'transaksi_detail.produk_id')
            ->whereDate('transaksi.tanggal', '>=', $tujuhHariLalu)
            ->selectRaw('
                produk.id as produk_id,
                produk.nama as produk_nama, 
                SUM(transaksi_detail.jumlah * produk_satuan_jual.jumlah_dalam_satuan_dasar) as total_terjual_satuan_dasar
            ')
            ->groupBy('produk.id', 'produk.nama')
            ->orderByDesc('total_terjual_satuan_dasar')
            ->limit(4)
            ->get();

        // 5. Tabel "Produk Perlu Perhatian"
        $perluPerhatian = $produkMarginTipis->merge($produkBelumUpdate)->unique('id')->take(6);

        return view('dashboard', compact(
            'totalProdukAktif', 
            'countMarginTipis', 
            'countBelumUpdate', 
            'perluPerhatian', 
            'produkTerlaris'
        ));
    }
}
