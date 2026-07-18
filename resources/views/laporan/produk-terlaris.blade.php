<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-sm font-bold text-text-primary leading-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">star</span>
                    {{ __('Produk Terlaris (Top 10)') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Daftar produk paling laku berdasarkan total jumlah yang terjual.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">
            
            {{-- Filter Section --}}
            <div class="bg-surface border border-outline-variant rounded-xl p-md">
                <form method="GET" action="{{ route('laporan.produk-terlaris') }}" class="flex flex-col md:flex-row md:items-end gap-4">
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

            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider w-16 text-center">Rank</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Produk</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider w-1/3">Total Terjual</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Total Omzet</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($produkTerlaris as $index => $p)
                                @php
                                    // Menghitung persentase bar relatif terhadap juara 1 (index 0)
                                    $maxTerjual = $produkTerlaris[0]->total_terjual_satuan_dasar > 0 ? $produkTerlaris[0]->total_terjual_satuan_dasar : 1;
                                    $percentage = min(100, max(1, ($p->total_terjual_satuan_dasar / $maxTerjual) * 100));
                                @endphp
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4 text-center">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-container text-on-primary-container font-bold text-lg">1</span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container font-bold text-lg">2</span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-tertiary-container text-on-tertiary-container font-bold text-lg">3</span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-surface-container-high text-on-surface-variant font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-text-primary">{{ $p->produk_nama }}</div>
                                        <div class="text-xs text-text-secondary mt-0.5">{{ $p->kategori_nama ?? 'Tanpa Kategori' }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm font-bold text-text-primary font-numeric-mono">
                                                {{ floatval($p->total_terjual_satuan_dasar) }} {{ $p->satuan_dasar_nama }}
                                            </span>
                                        </div>
                                        <div class="w-full bg-surface-container-high rounded-full h-2">
                                            <div class="{{ $index === 0 ? 'bg-primary' : 'bg-primary/70' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-primary text-right font-numeric-mono">
                                        Rp {{ number_format($p->total_omzet, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">hourglass_empty</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Belum ada data penjualan</h3>
                                        <p class="text-text-secondary text-sm">Tidak ditemukan riwayat transaksi pada periode tanggal yang dipilih.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
