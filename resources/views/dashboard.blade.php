<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>

    {{-- Search di topbar --}}
    <x-slot name="search">
        <div class="relative hidden md:block">
            <input
                type="text"
                placeholder="Cari Produk..."
                class="bg-surface-container-low border-none rounded-full px-lg py-2 w-64 text-body-md focus:ring-2 focus:ring-primary focus:outline-none"
            >
            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary">search</span>
        </div>
    </x-slot>

    {{-- === Summary Bento Grid === --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-lg">

        {{-- Card 1: Produk Aktif --}}
        <div class="bg-surface-container-lowest p-lg rounded-xl border border-table-border flex items-center justify-between">
            <div>
                <p class="font-mono text-label-caps text-text-secondary mb-1 uppercase tracking-widest">Produk Aktif</p>
                <p class="font-headline text-display-price text-primary">
                    124 <span class="text-body-md font-normal text-text-secondary">Item</span>
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
                    12 <span class="text-body-md font-normal text-text-secondary">Item</span>
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
                    08 <span class="text-body-md font-normal text-text-secondary">Item</span>
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
                <table class="w-full text-left border-collapse">
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

                        {{-- Row: Beras Cianjur (margin aman) --}}
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-text-secondary shrink-0">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">potted_plant</span>
                                    </div>
                                    <span class="font-body text-table-data font-semibold text-text-primary">Beras Cianjur</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono text-text-secondary">Rp 12.500</td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono font-bold text-text-primary">Rp 14.000</td>
                            <td class="px-lg py-md text-center">
                                <span class="px-3 py-1 rounded-lg bg-margin-success/15 text-margin-success font-bold text-label-caps">12%</span>
                            </td>
                            <td class="px-lg py-md text-center">
                                <a href="#" class="material-symbols-outlined text-text-secondary hover:text-primary transition-colors">edit_square</a>
                            </td>
                        </tr>

                        {{-- Row: Cabai Rawit (margin tipis/rugi) --}}
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-text-secondary shrink-0">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">nutrition</span>
                                    </div>
                                    <span class="font-body text-table-data font-semibold text-text-primary">Cabai Rawit</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono text-text-secondary">Rp 45.000</td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono font-bold text-text-primary">Rp 46.500</td>
                            <td class="px-lg py-md text-center">
                                <span class="px-3 py-1 rounded-lg bg-margin-danger/15 text-margin-danger font-bold text-label-caps">3.2%</span>
                            </td>
                            <td class="px-lg py-md text-center">
                                <a href="#" class="material-symbols-outlined text-text-secondary hover:text-primary transition-colors">edit_square</a>
                            </td>
                        </tr>

                        {{-- Row: Bawang Merah (margin warning) --}}
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-text-secondary shrink-0">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">agriculture</span>
                                    </div>
                                    <span class="font-body text-table-data font-semibold text-text-primary">Bawang Merah</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono text-text-secondary">Rp 28.000</td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono font-bold text-text-primary">Rp 30.500</td>
                            <td class="px-lg py-md text-center">
                                <span class="px-3 py-1 rounded-lg bg-margin-warning/15 text-margin-warning font-bold text-label-caps">8.9%</span>
                            </td>
                            <td class="px-lg py-md text-center">
                                <a href="#" class="material-symbols-outlined text-text-secondary hover:text-primary transition-colors">edit_square</a>
                            </td>
                        </tr>

                        {{-- Row: Tomat Cherry (margin aman) --}}
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-text-secondary shrink-0">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">eco</span>
                                    </div>
                                    <span class="font-body text-table-data font-semibold text-text-primary">Tomat Cherry</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono text-text-secondary">Rp 18.000</td>
                            <td class="px-lg py-md text-right font-mono text-numeric-mono font-bold text-text-primary">Rp 20.000</td>
                            <td class="px-lg py-md text-center">
                                <span class="px-3 py-1 rounded-lg bg-margin-success/15 text-margin-success font-bold text-label-caps">11.1%</span>
                            </td>
                            <td class="px-lg py-md text-center">
                                <a href="#" class="material-symbols-outlined text-text-secondary hover:text-primary transition-colors">edit_square</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Panel Tren Harga (1/3 lebar) --}}
        <div class="space-y-md">
            <div class="flex items-center justify-between mb-sm">
                <h2 class="font-headline text-headline-md text-text-primary">Tren Harga (7 Hari)</h2>
                <span class="material-symbols-outlined text-text-secondary">show_chart</span>
            </div>

            <div class="bg-surface-container-lowest rounded-xl border border-table-border p-md divide-y divide-table-border">

                {{-- Tren: Beras Cianjur (naik) --}}
                <div class="py-sm flex items-center justify-between gap-md">
                    <div class="min-w-0">
                        <p class="font-body text-table-data font-bold text-text-primary truncate">Beras Cianjur</p>
                        <p class="text-xs text-text-secondary">Rp 14.000/kg</p>
                    </div>
                    <div class="w-20 h-10 shrink-0">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 100 30">
                            <path class="sparkline" d="M0,25 L20,20 L40,22 L60,15 L80,10 L100,5"
                                  fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="text-margin-success text-xs font-bold shrink-0">+2.1%</span>
                </div>

                {{-- Tren: Cabai Rawit (turun) --}}
                <div class="py-sm flex items-center justify-between gap-md">
                    <div class="min-w-0">
                        <p class="font-body text-table-data font-bold text-text-primary truncate">Cabai Rawit</p>
                        <p class="text-xs text-text-secondary">Rp 46.500/kg</p>
                    </div>
                    <div class="w-20 h-10 shrink-0">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 100 30">
                            <path class="sparkline" d="M0,5 L20,15 L40,12 L60,20 L80,25 L100,28"
                                  fill="none" stroke="#D32F2F" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="text-margin-danger text-xs font-bold shrink-0">-4.5%</span>
                </div>

                {{-- Tren: Bawang Merah (sedikit naik) --}}
                <div class="py-sm flex items-center justify-between gap-md">
                    <div class="min-w-0">
                        <p class="font-body text-table-data font-bold text-text-primary truncate">Bawang Merah</p>
                        <p class="text-xs text-text-secondary">Rp 30.500/kg</p>
                    </div>
                    <div class="w-20 h-10 shrink-0">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 100 30">
                            <path class="sparkline" d="M0,20 L20,18 L40,15 L60,16 L80,14 L100,12"
                                  fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="text-margin-success text-xs font-bold shrink-0">+1.2%</span>
                </div>

                {{-- Tren: Minyak Goreng (stabil) --}}
                <div class="py-sm flex items-center justify-between gap-md">
                    <div class="min-w-0">
                        <p class="font-body text-table-data font-bold text-text-primary truncate">Minyak Goreng</p>
                        <p class="text-xs text-text-secondary">Rp 17.500/lt</p>
                    </div>
                    <div class="w-20 h-10 shrink-0">
                        <svg class="w-full h-full overflow-visible" viewBox="0 0 100 30">
                            <path class="sparkline" d="M0,15 L20,15 L40,16 L60,15 L80,15 L100,15"
                                  fill="none" stroke="#FFB300" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <span class="text-text-secondary text-xs font-bold shrink-0">0.0%</span>
                </div>

            </div>
        </div>

    </div>

    {{-- FAB: Update Harga Cepat — sesuai SKILL: laravel-design-tokens --}}
    {{-- ponytail: lingkaran hijau bg-primary, icon putih, kanan bawah, hanya halaman kasir/dashboard --}}
    <a href="#"
       id="fab-update-harga"
       class="fixed bottom-lg right-lg bg-primary hover:bg-primary-container text-on-primary rounded-full px-lg py-4 flex items-center gap-md shadow-lg transition-all duration-150 active:scale-95 z-50 group">
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
