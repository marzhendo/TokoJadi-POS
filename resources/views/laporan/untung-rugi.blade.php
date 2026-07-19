<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-sm font-bold text-text-primary leading-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">monitoring</span>
                    {{ __('Laporan Untung-Rugi') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Analisis margin keuntungan dari selisih Harga Jual dan Harga Modal secara akurat.') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">
            
            {{-- Filter Section --}}
            <div class="bg-surface border border-outline-variant rounded-xl p-md">
                <form method="GET" action="{{ route('laporan.untung-rugi') }}" class="flex flex-col md:flex-row md:items-end gap-4">
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start">
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1">Total Modal</span>
                    <span class="font-headline text-display-price font-bold text-text-primary">Rp {{ number_format($totalModal, 0, ',', '.') }}</span>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start">
                    <span class="text-xs font-bold text-text-secondary uppercase mb-1">Total Omzet</span>
                    <span class="font-headline text-display-price font-bold text-text-primary">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</span>
                </div>
                <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-col justify-center items-start md:col-span-2">
                    <div class="flex items-center justify-between w-full">
                        <div>
                            <span class="text-xs font-bold text-text-secondary uppercase mb-1">Total Keuntungan Bersih</span>
                            <span class="font-headline text-display-price font-bold {{ $totalUntung >= 0 ? 'text-margin-success' : 'text-margin-danger' }}">
                                {{ $totalUntung < 0 ? '-' : '' }}Rp {{ number_format(abs($totalUntung), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-text-secondary uppercase mb-1 block">Margin</span>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg font-bold text-lg {{ $marginPersen >= 0 ? 'bg-success-container text-margin-success' : 'bg-error-container text-margin-danger' }}">
                                {{ number_format($marginPersen, 1, ',', '.') }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="overflow-x-auto w-full">
<table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Produk</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Modal Item</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Omzet Item</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($breakdown as $p)
                                @php
                                    $marginPercentRow = $p->total_omzet > 0 ? ($p->untung_bersih / $p->total_omzet) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-text-primary">{{ $p->produk_nama }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-text-secondary text-right font-numeric-mono">
                                        Rp {{ number_format($p->total_modal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-text-primary text-right font-numeric-mono">
                                        Rp {{ number_format($p->total_omzet, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-right font-numeric-mono">
                                        <div class="flex flex-col items-end">
                                            <span class="{{ $p->untung_bersih >= 0 ? 'text-margin-success' : 'text-margin-danger' }}">
                                                {{ $p->untung_bersih < 0 ? '-' : '' }}Rp {{ number_format(abs($p->untung_bersih), 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] {{ $marginPercentRow >= 0 ? 'text-margin-success' : 'text-margin-danger' }} font-bold bg-surface-container-highest px-1.5 py-0.5 rounded mt-0.5">
                                                {{ number_format($marginPercentRow, 1, ',', '.') }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">inventory</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Belum ada data pada periode ini</h3>
                                        <p class="text-text-secondary text-sm">Coba sesuaikan filter tanggal di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
</div>
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
