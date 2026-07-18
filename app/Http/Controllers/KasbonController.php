<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    public function index()
    {
        $transaksiKasbon = Transaksi::with(['pelanggan', 'pembayaranKasbon'])
            ->where('metode_bayar', 'kasbon')
            ->where('status_kasbon', 'belum_lunas')
            ->orderBy('tanggal', 'asc') // Paling lama duluan
            ->paginate(15);

        return view('kasbon.index', compact('transaksiKasbon'));
    }

    public function bayar(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0.01',
            'tanggal_bayar' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated, $transaksi) {
                // Lock for update
                $transaksiLock = Transaksi::where('id', $transaksi->id)->lockForUpdate()->firstOrFail();

                if ($transaksiLock->status_kasbon === 'lunas') {
                    throw new \Exception('Kasbon ini sudah lunas.');
                }

                if ($validated['jumlah_bayar'] > $transaksiLock->sisaKasbon()) {
                    throw new \Exception('Jumlah bayar melebihi sisa hutang kasbon.');
                }

                $transaksiLock->pembayaranKasbon()->create([
                    'jumlah_bayar' => $validated['jumlah_bayar'],
                    'tanggal_bayar' => $validated['tanggal_bayar'],
                ]);

                // Auto update status if fully paid
                if ($transaksiLock->sisaKasbon() <= 0) {
                    $transaksiLock->update(['status_kasbon' => 'lunas']);
                }
            });

            return back()->with('success', 'Pembayaran kasbon berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
