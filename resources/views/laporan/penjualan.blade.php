<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-sm font-bold text-text-primary leading-tight">
                    {{ __('Laporan Penjualan') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Ringkasan transaksi dan omzet berdasarkan rentang tanggal.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">
            
            {{-- Filter Section --}}
            <div class="bg-surface border border-outline-variant rounded-xl p-md">
                <form method="GET" action="{{ route('laporan.penjualan') }}" class="flex flex-col md:flex-row md:items-end gap-4">
                    <div class="flex-1 max-w-xs">
                        <label for="dari" class="block text-xs font-bold text-text-secondary uppercase mb-1">Dari Tanggal</label>
                        <input type="date" id="dari" name="dari" value="{{ request('dari') }}" 
                               class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>
                    <div class="flex-1 max-w-xs">
                        <label for="sampai" class="block text-xs font-bold text-text-secondary uppercase mb-1">Sampai Tanggal</label>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start">
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1">Total Transaksi</span>
                    <span class="font-headline text-display-price font-bold text-primary">{{ number_format($totalTransaksi, 0, ',', '.') }}</span>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start">
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1">Total Omzet</span>
                    <span class="font-headline text-display-price font-bold text-primary">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start">
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1">Rata-rata Nilai Transaksi</span>
                    <span class="font-headline text-display-price font-bold text-primary">Rp {{ number_format($rataRata, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto hidden sm:block">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider w-32">Tanggal</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Jumlah Item</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Metode Bayar</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($transaksi as $t)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4 text-sm text-text-primary whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}
                                        <div class="text-xs text-text-secondary mt-0.5">{{ \Carbon\Carbon::parse($t->tanggal)->format('H:i') }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-1 bg-surface-container-high rounded text-xs font-bold text-text-primary">
                                            {{ $t->detail->sum('jumlah') }} Item
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-1 bg-surface-container-high border border-outline-variant rounded text-xs font-bold uppercase tracking-wider text-text-secondary">
                                            {{ $t->metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-text-primary text-right font-numeric-mono">
                                        Rp {{ number_format($t->total_belanja, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-text-secondary">
                                        <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">receipt_long</span>
                                        <p>Tidak ada transaksi pada rentang tanggal ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="sm:hidden flex flex-col divide-y divide-table-border">
                    @forelse($transaksi as $t)
                        <div class="p-4 hover:bg-surface-container-lowest transition-colors flex flex-col gap-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-text-primary block">{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</span>
                                    <span class="text-xs text-text-secondary">{{ \Carbon\Carbon::parse($t->tanggal)->format('H:i') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-numeric-mono text-primary font-bold text-lg block">Rp {{ number_format($t->total_belanja, 0, ',', '.') }}</span>
                                    <span class="inline-flex items-center px-2 py-1 bg-surface-container-high border border-outline-variant rounded text-[10px] font-bold uppercase tracking-wider text-text-secondary mt-1">
                                        {{ $t->metode_bayar }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-t border-table-border/50">
                                <span class="text-xs font-bold text-text-secondary uppercase mb-1 block">Detail Item:</span>
                                <div class="text-sm text-text-primary">
                                    Total <span class="font-bold">{{ $t->detail->sum('jumlah') }} Item</span> terjual dalam transaksi ini.
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-text-secondary">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">receipt_long</span>
                            <p>Tidak ada transaksi pada rentang tanggal ini.</p>
                        </div>
                    @endforelse
                </div>
                
                @if($transaksi->hasPages())
                    <div class="p-4 border-t border-table-border bg-surface-container-lowest">
                        {{ $transaksi->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
