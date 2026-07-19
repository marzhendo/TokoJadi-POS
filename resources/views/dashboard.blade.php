<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>

    {{-- Search di topbar --}}
    <x-slot name="search">
        <form action="{{ route('produk.index') }}" method="GET" class="relative hidden md:block">
            <input
                type="text"
                name="search"
                placeholder="Cari Produk..."
                class="bg-surface-container-low border-none rounded-full px-lg py-2 w-64 text-body-md focus:ring-2 focus:ring-primary focus:outline-none"
            >
            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary">search</span>
        </form>
    </x-slot>

    {{-- === Summary Bento Grid === --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-lg">

        {{-- Card 1: Produk Aktif --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-table-border flex items-center justify-between">
            <div>
                <p class="font-mono text-label-caps text-text-secondary mb-1 uppercase tracking-widest">Produk Aktif</p>
                <p class="font-headline text-display-price text-primary">
                    {{ number_format($totalProdukAktif, 0, ',', '.') }} <span class="text-body-md font-normal text-text-secondary">Item</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-primary-container/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 24px;">inventory</span>
            </div>
        </div>

        {{-- Card 2: Margin Tipis (warning) --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-table-border border-l-4 border-l-margin-warning flex items-center justify-between">
            <div>
                <p class="font-mono text-label-caps text-text-secondary mb-1 uppercase tracking-widest">Margin Tipis/Rugi</p>
                <p class="font-headline text-display-price text-margin-warning">
                    {{ number_format($countMarginTipis, 0, ',', '.') }} <span class="text-body-md font-normal text-text-secondary">Item</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-margin-warning/10 flex items-center justify-center text-margin-warning">
                <span class="material-symbols-outlined" style="font-size: 24px;">trending_down</span>
            </div>
        </div>

        {{-- Card 3: Belum Update (danger) --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-table-border border-l-4 border-l-margin-danger flex items-center justify-between">
            <div>
                <p class="font-mono text-label-caps text-text-secondary mb-1 uppercase tracking-widest">Belum Update (&gt;3 hari)</p>
                <p class="font-headline text-display-price text-margin-danger">
                    {{ number_format($countBelumUpdate, 0, ',', '.') }} <span class="text-body-md font-normal text-text-secondary">Item</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-margin-danger/10 flex items-center justify-center text-margin-danger">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1; font-size: 24px;">timer_off</span>
            </div>
        </div>

    </div>

    {{-- === Main Content: Tabel + Tren === --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-lg">

        {{-- Tabel Produk Perlu Perhatian (2/3 lebar) --}}
        <div class="xl:col-span-2 space-y-md">
            <div class="flex items-center justify-between mb-sm">
                <h2 class="font-headline text-headline-md text-text-primary">Produk Perlu Perhatian</h2>
                <a href="#" class="text-primary font-bold text-body-md hover:underline">Lihat Semua</a>
            </div>

            <div class="bg-surface-container-lowest rounded-xl border border-table-border overflow-hidden">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase">Nama Produk</th>
                            <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-right">Harga Beli</th>
                            <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-right">Harga Jual</th>
                            <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-center">Margin</th>
                            <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-table-border">

                        @forelse($perluPerhatian as $p)
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="px-lg py-md">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-text-secondary shrink-0">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">warning</span>
                                        </div>
                                        <span class="font-body text-table-data font-semibold text-text-primary">{{ $p->nama }}</span>
                                    </div>
                                </td>
                                <td class="px-lg py-md text-right font-mono text-numeric-mono text-text-secondary">
                                    Rp {{ number_format($p->harga_modal_per_satuan_dasar, 0, ',', '.') }}
                                </td>
                                <td class="px-lg py-md text-right font-mono text-numeric-mono text-text-secondary">
                                    @if($p->is_margin_tipis)
                                        <span class="text-xs text-margin-danger">Peringatan Margin</span>
                                    @elseif($p->is_belum_update)
                                        <span class="text-xs text-text-secondary">Lama tak update</span>
                                    @endif
                                </td>
                                <td class="px-lg py-md text-center">
                                    @if($p->is_margin_tipis)
                                        <span class="px-3 py-1 rounded-lg {{ $p->margin_kritis < 10 ? 'bg-margin-danger/15 text-margin-danger' : 'bg-margin-warning/15 text-margin-warning' }} font-bold text-label-caps">
                                            {{ $p->margin_kritis }}%
                                        </span>
                                    @elseif($p->is_belum_update)
                                        <span class="px-3 py-1 rounded-lg bg-margin-danger/15 text-margin-danger font-bold text-label-caps">
                                            >3 HARI
                                        </span>
                                    @endif
                                </td>
                                <td class="px-lg py-md text-center">
                                    <a href="{{ route('produk.edit', $p->id) }}" class="material-symbols-outlined text-text-secondary hover:text-primary transition-colors">edit_square</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-lg py-xl text-center text-text-secondary text-body-md">
                                    <span class="material-symbols-outlined block mx-auto mb-sm" style="font-size:32px;opacity:.3">check_circle</span>
                                    Semua produk dalam kondisi aman.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{-- Panel Terlaris (1/3 lebar) --}}
        <div class="space-y-md">
            <div class="flex items-center justify-between mb-sm">
                <h2 class="font-headline text-headline-md text-text-primary">Terlaris (7 Hari)</h2>
                <span class="material-symbols-outlined text-text-secondary">workspace_premium</span>
            </div>

            <div class="bg-surface-container-lowest rounded-xl border border-table-border p-md divide-y divide-table-border">

                @forelse($produkTerlaris as $pt)
                    <div class="py-sm flex items-center justify-between gap-md">
                        <div class="min-w-0">
                            <p class="font-body text-table-data font-bold text-text-primary truncate">{{ $pt->produk_nama }}</p>
                        </div>
                        <span class="text-text-primary text-xs font-bold shrink-0">
                            {{ number_format($pt->total_terjual_satuan_dasar, 0, ',', '.') }} Terjual
                        </span>
                    </div>
                @empty
                    <div class="py-xl text-center text-text-secondary text-body-md">
                        <span class="material-symbols-outlined block mx-auto mb-sm" style="font-size:32px;opacity:.3">receipt_long</span>
                        Belum ada penjualan.
                    </div>
                @endforelse

            </div>
        </div>

    </div>

    {{-- FAB: Update Harga Cepat — sesuai SKILL: laravel-design-tokens --}}
    {{-- ponytail: lingkaran hijau bg-primary, icon putih, kanan bawah, hanya halaman kasir/dashboard --}}
    <a href="{{ route('produk.index', ['status' => 'menipis']) }}"
       id="fab-update-harga"
       class="fixed bottom-[80px] sm:bottom-lg right-lg bg-primary hover:bg-primary-container text-on-primary rounded-full px-lg py-4 flex items-center gap-md shadow-lg transition-all duration-150 active:scale-95 z-50 group">
        <span class="material-symbols-outlined" style="font-size: 20px;">bolt</span>
        <span class="font-bold text-body-lg">Update Harga Cepat</span>
    </a>

    @push('scripts')
    <script>
        // Micro-interaction: FAB hover lift
        const fab = document.getElementById('fab-update-harga');
        fab.addEventListener('mouseenter', () => fab.classList.add('shadow-xl', '-translate-y-1'));
        fab.addEventListener('mouseleave', () => fab.classList.remove('shadow-xl', '-translate-y-1'));

        // Pulse animasi pada warning cards tiap 3 detik
        const warnCards = document.querySelectorAll('.border-l-margin-warning, .border-l-margin-danger');
        setInterval(() => {
            warnCards.forEach(card => {
                const icon = card.querySelector('.material-symbols-outlined');
                if (icon) {
                    icon.style.transform = 'scale(1.15)';
                    setTimeout(() => icon.style.transform = 'scale(1)', 300);
                }
            });
        }, 3000);
    </script>
    @endpush

</x-app-layout>
