<x-app-layout>
    <x-slot name="pageTitle">
        Riwayat & Bagi Hasil Penitip
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-2">
                <a href="{{ route('titipan.index') }}" class="text-text-secondary hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <p class="text-sm text-text-secondary">
                    Laporan utang kepada penitip dan penghasilan komisi toko.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">
            
            {{-- Filter Section --}}
            <div class="bg-surface border border-outline-variant rounded-xl p-md">
                <form method="GET" action="{{ route('titipan.riwayat') }}" class="flex flex-col md:flex-row md:items-end gap-4">
                    <div class="flex-1 max-w-xs">
                        <label for="dari" class="block text-xs font-bold text-text-secondary uppercase mb-1">Dari Tanggal Terjual</label>
                        <input type="date" id="dari" name="dari" value="{{ request('dari') }}" 
                               class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>
                    <div class="flex-1 max-w-xs">
                        <label for="sampai" class="block text-xs font-bold text-text-secondary uppercase mb-1">Sampai Tanggal Terjual</label>
                        <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}" 
                               class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto px-4 py-2 bg-surface-container-high text-on-surface-variant font-bold text-sm rounded-lg hover:bg-outline-variant transition-colors">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-error-container/30 to-transparent"></div>
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1 z-10">Total Kewajiban ke Penitip (Utang)</span>
                    <span class="font-headline text-display-price font-bold text-margin-danger z-10">
                        Rp {{ number_format($totalDibayarPenitip, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-text-secondary mt-2 z-10">Uang yang harus diserahkan kepada para penitip barang.</p>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start relative overflow-hidden">
                    <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-success-container/30 to-transparent"></div>
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1 z-10">Total Komisi Bersih Toko</span>
                    <span class="font-headline text-display-price font-bold text-margin-success z-10">
                        Rp {{ number_format($totalKomisi, 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-text-secondary mt-2 z-10">Penghasilan murni toko dari barang konsinyasi yang laku.</p>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Nama Penitip</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Total Barang Laku</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Hak Penitip (Utang)</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Komisi Toko</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($breakdown as $p)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-text-primary">{{ $p->nama_penitip }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-numeric-mono">
                                        {{ floatval($p->total_barang_terjual) }} item
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-margin-danger text-right font-numeric-mono">
                                        Rp {{ number_format($p->total_hak_penitip, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-margin-success text-right font-numeric-mono">
                                        + Rp {{ number_format($p->total_komisi_toko, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">receipt_long</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Belum ada barang laku</h3>
                                        <p class="text-text-secondary text-sm">Tidak ada riwayat penjualan barang titipan pada periode tanggal ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($breakdown->hasPages())
                    <div class="p-4 border-t border-table-border bg-surface-container-lowest">
                        {{ $breakdown->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
