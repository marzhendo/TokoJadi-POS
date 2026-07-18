<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline text-headline-sm font-bold text-text-primary leading-tight">
                    {{ __('Stok Menipis') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Daftar produk yang stoknya sudah mencapai batas minimum atau habis.') }}
                </p>
            </div>
            <div>
                <a href="{{ route('produk.index') }}" class="px-4 py-2 bg-surface-container-high text-text-primary font-bold text-sm rounded-lg hover:bg-outline-variant transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                    Kelola Produk
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">
            
            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Produk</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Stok Minimum</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Stok Saat Ini</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($produk as $p)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-text-primary">{{ $p->nama }}</div>
                                        <div class="text-xs text-text-secondary mt-0.5">{{ $p->kategori->nama ?? 'Tanpa Kategori' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-text-secondary text-right font-numeric-mono">
                                        {{ floatval($p->stok_minimum) }} {{ $p->satuanDasar->nama }}
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-text-primary text-right font-numeric-mono">
                                        {{ floatval($p->stok_saat_ini) }} {{ $p->satuanDasar->nama }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($p->stok_saat_ini <= 0)
                                            <span class="inline-flex items-center px-2 py-1 bg-error-container text-margin-danger rounded text-xs font-bold uppercase tracking-wider">
                                                Habis
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 bg-warning-container text-margin-warning rounded text-xs font-bold uppercase tracking-wider">
                                                Menipis
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">check_circle</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Semua Stok Aman</h3>
                                        <p class="text-text-secondary text-sm">Tidak ada produk yang stoknya berada di bawah batas minimum.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($produk->hasPages())
                    <div class="p-4 border-t border-table-border bg-surface-container-lowest">
                        {{ $produk->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
