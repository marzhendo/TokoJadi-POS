<?php

namespace App\Http\Controllers;

use App\Models\TitipanBarang;
use App\Models\TitipanPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TitipanController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'aktif');
        $titipanBarangs = TitipanBarang::where('status', $status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('titipan.index', compact('titipanBarangs', 'status'));
    }

    public function create(): View
    {
        return view('titipan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'komisi_toko' => 'required|numeric|min:0',
            'jumlah_dititipkan' => 'required|numeric|min:0',
        ]);

        $validated['status'] = 'aktif';
        $validated['jumlah_terjual'] = 0;

        TitipanBarang::create($validated);

        return redirect()->route('titipan.index')->with('success', 'Titipan barang berhasil ditambahkan.');
    }

    public function catatPenjualan(Request $request, TitipanBarang $titipan)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0.001',
            'tanggal' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated, $titipan) {
                // Lock for update to prevent race conditions as per plan
                $titipanLock = TitipanBarang::where('id', $titipan->id)->lockForUpdate()->firstOrFail();

                if ($titipanLock->status === 'selesai') {
                    throw new \Exception('Status barang titipan sudah selesai.');
                }

                if ($validated['jumlah'] > $titipanLock->sisaStok()) {
                    throw new \Exception('Jumlah penjualan melebihi sisa stok barang titipan.');
                }

                $hargaJualSaatItu = $titipanLock->harga_jual;
                $komisiSaatItu = $titipanLock->komisi_toko;
                $totalUntukPenitip = $validated['jumlah'] * ($hargaJualSaatItu - $komisiSaatItu);
                $totalKomisiToko = $validated['jumlah'] * $komisiSaatItu;

                TitipanPenjualan::create([
                    'titipan_barang_id' => $titipanLock->id,
                    'jumlah' => $validated['jumlah'],
                    'harga_jual_saat_itu' => $hargaJualSaatItu,
                    'komisi_saat_itu' => $komisiSaatItu,
                    'total_untuk_penitip' => $totalUntukPenitip,
                    'total_komisi_toko' => $totalKomisiToko,
                    'tanggal' => $validated['tanggal'],
                ]);

                $titipanLock->increment('jumlah_terjual', $validated['jumlah']);
                
                // Auto close status if fully sold
                if ($titipanLock->sisaStok() <= 0) {
                    $titipanLock->update(['status' => 'selesai']);
                }
            });

            return redirect()->route('titipan.index')->with('success', 'Penjualan titipan berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function riwayat(Request $request): View
    {
        $dari = $request->input('dari', Carbon::now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', Carbon::now()->toDateString());

        $request->merge(['dari' => $dari, 'sampai' => $sampai]);

        $baseQuery = DB::table('titipan_penjualans')
            ->join('titipan_barangs', 'titipan_barangs.id', '=', 'titipan_penjualans.titipan_barang_id')
            ->when($request->dari, fn ($q, $v) => $q->whereDate('titipan_penjualans.tanggal', '>=', $v))
            ->when($request->sampai, fn ($q, $v) => $q->whereDate('titipan_penjualans.tanggal', '<=', $v));

        $ringkasan = (clone $baseQuery)
            ->selectRaw('
                SUM(titipan_penjualans.total_untuk_penitip) as total_dibayar_penitip,
                SUM(titipan_penjualans.total_komisi_toko) as total_komisi
            ')
            ->first();

        $totalDibayarPenitip = $ringkasan->total_dibayar_penitip ?? 0;
        $totalKomisi = $ringkasan->total_komisi ?? 0;

        $breakdown = (clone $baseQuery)
            ->selectRaw('
                titipan_barangs.nama_penitip,
                SUM(titipan_penjualans.jumlah) as total_barang_terjual,
                SUM(titipan_penjualans.total_untuk_penitip) as total_hak_penitip,
                SUM(titipan_penjualans.total_komisi_toko) as total_komisi_toko
            ')
            ->groupBy('titipan_barangs.nama_penitip')
            ->orderByRaw('total_hak_penitip DESC')
            ->paginate(20)
            ->withQueryString();

        return view('titipan.riwayat', compact('breakdown', 'totalDibayarPenitip', 'totalKomisi'));
    }
}
