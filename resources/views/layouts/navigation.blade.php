{{-- Sidebar navigasi vertikal — 80px, fixed kiri, icon-only --}}
{{-- Sesuai desain Stitch "Dashboard Utama - Toko Jadi" --}}
<nav class="fixed left-0 top-0 h-full w-20 z-50 flex flex-col items-center py-md bg-surface-container-lowest border-r border-table-border">
    {{-- Logo / Brand --}}
    <div class="mb-xl">
        <a href="{{ route('dashboard') }}" title="Toko Jadi POS">
            <span class="font-headline text-headline-md font-bold text-primary">TJ</span>
        </a>
    </div>

    {{-- Nav items --}}
    <div class="flex flex-col items-center flex-1 space-y-sm">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           title="Dashboard"
           class="p-3 rounded-xl transition-colors duration-150 active:scale-95
                  {{ request()->routeIs('dashboard')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>
                home
            </span>
        </a>

        {{-- Master Produk --}}
        <a href="{{ route('produk.index') }}"
           title="Master Produk"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('produk.*')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('produk.*')) style="font-variation-settings: 'FILL' 1;" @endif>
                inventory_2
            </span>
        </a>

        {{-- Riwayat Transaksi (route belum ada) --}}
        <a href="#"
           title="Riwayat Transaksi"
           class="p-3 rounded-xl transition-colors duration-150 text-on-surface-variant hover:bg-surface-container-high">
            <span class="material-symbols-outlined">history</span>
        </a>

        {{-- Laporan (route belum ada) --}}
        <a href="#"
           title="Laporan"
           class="p-3 rounded-xl transition-colors duration-150 text-on-surface-variant hover:bg-surface-container-high">
            <span class="material-symbols-outlined">analytics</span>
        </a>
    </div>

    {{-- Bottom: settings --}}
    <a href="{{ route('profile.edit') }}"
       title="Profil & Pengaturan"
       class="p-3 rounded-xl transition-colors duration-150 text-on-surface-variant hover:bg-surface-container-high mt-auto">
        <span class="material-symbols-outlined">settings</span>
    </a>
</nav>
