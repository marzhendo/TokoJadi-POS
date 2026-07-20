<x-app-layout>
    <x-slot name="pageTitle">Riwayat Transaksi</x-slot>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="font-headline-lg text-headline-lg text-text-primary font-bold">Riwayat Transaksi</h1>
        <p class="text-text-secondary font-body-md text-sm">Daftar semua transaksi yang telah selesai.</p>
    </div>
    
    <a href="{{ route('transaksi.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold flex items-center gap-2 hover:opacity-90">
        <span class="material-symbols-outlined" data-icon="add">add</span> Kasir Baru
    </a>
</div>

<div class="bg-surface-white border border-table-border rounded-xl shadow-sm mb-6 p-4">
    <form action="{{ route('transaksi.index') }}" method="GET" class="flex items-end gap-4">
        <div>
            <label class="block text-xs font-bold text-text-secondary mb-1">DARI TANGGAL</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary">
        </div>
        <div>
            <label class="block text-xs font-bold text-text-secondary mb-1">SAMPAI TANGGAL</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary">
        </div>
        <button type="submit" class="bg-surface-container-high text-on-surface px-4 py-2 rounded-lg font-bold hover:bg-surface-dim">Filter</button>
        <a href="{{ route('transaksi.index') }}" class="text-text-secondary hover:underline text-sm font-bold ml-2">Reset</a>
    </form>
</div>

<div class="bg-surface-white border border-table-border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-table-border">
                <tr>
                    <th class="p-4 font-label-caps text-label-caps text-text-secondary">Tanggal</th>
                    <th class="p-4 font-label-caps text-label-caps text-text-secondary">Detail Item</th>
                    <th class="p-4 font-label-caps text-label-caps text-text-secondary">Metode Bayar</th>
                    <th class="p-4 font-label-caps text-label-caps text-text-secondary text-right">Total Belanja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-table-border">
                @forelse ($transaksi as $trx)
                    <tr class="hover:bg-surface-container-lowest transition-colors">
                        <td class="p-4 align-top">
                            <span class="font-bold text-text-primary block">{{ $trx->tanggal->format('d M Y') }}</span>
                            <span class="text-xs text-text-secondary">{{ $trx->tanggal->format('H:i') }}</span>
                        </td>
                        <td class="p-4 align-top">
                            <ul class="space-y-1">
                                @foreach($trx->detail as $dtl)
                                    <li class="text-sm">
                                        <span class="font-bold text-text-primary">{{ $dtl->produkSatuanJual->produk->nama ?? 'Produk Dihapus' }}</span> 
                                        <span class="text-text-secondary">({{ floatval($dtl->jumlah) }} x Rp {{ number_format($dtl->harga_satuan, 0, ',', '.') }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-4 align-top">
                            @if($trx->metode_bayar === 'cash')
                                <span class="px-2 py-1 bg-success-margin/10 text-success-margin text-[11px] font-bold rounded-lg border border-success-margin/20 uppercase tracking-wider">CASH</span>
                            @else
                                <span class="px-2 py-1 bg-warning-margin/10 text-warning-margin text-[11px] font-bold rounded-lg border border-warning-margin/20 uppercase tracking-wider">KASBON</span>
                            @endif
                        </td>
                        <td class="p-4 text-right align-top">
                            <span class="font-numeric-mono text-primary font-bold">Rp {{ number_format($trx->total_belanja, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-text-secondary">
                            <span class="material-symbols-outlined block text-4xl mb-2 opacity-50">receipt_long</span>
                            Belum ada riwayat transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-table-border">
        {{ $transaksi->withQueryString()->links() }}
    </div>
</div>
</x-app-layout>
