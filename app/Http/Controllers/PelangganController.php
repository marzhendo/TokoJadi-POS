<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = \App\Models\Pelanggan::orderBy('nama')->paginate(15);
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
        ]);

        \App\Models\Pelanggan::create($validated);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(\App\Models\Pelanggan $pelanggan)
    {
        // Get all kasbon transactions for this customer
        $transaksiKasbon = $pelanggan->transaksi()
            ->with('pembayaranKasbon')
            ->where('metode_bayar', 'kasbon')
            ->orderBy('status_kasbon', 'asc') // belum_lunas first
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('pelanggan.show', compact('pelanggan', 'transaksiKasbon'));
    }
}
