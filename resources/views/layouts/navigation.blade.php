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

        {{-- Kasir --}}
        <a href="{{ route('transaksi.create') }}"
           title="Kasir"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('transaksi.create')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('transaksi.create')) style="font-variation-settings: 'FILL' 1;" @endif>
                point_of_sale
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

        {{-- Riwayat Transaksi --}}
        <a href="{{ route('transaksi.index') }}"
           title="Riwayat Transaksi"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('transaksi.index')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('transaksi.index')) style="font-variation-settings: 'FILL' 1;" @endif>
                history
            </span>
        </a>

        {{-- Laporan Penjualan --}}
        <a href="{{ route('laporan.penjualan') }}"
           title="Laporan Penjualan"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('laporan.penjualan')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('laporan.penjualan')) style="font-variation-settings: 'FILL' 1;" @endif>
                analytics
            </span>
        </a>

        {{-- Laporan Stok Menipis --}}
        <a href="{{ route('laporan.stok-menipis') }}"
           title="Stok Menipis"
           class="p-3 rounded-xl transition-colors duration-150 relative
                  {{ request()->routeIs('laporan.stok-menipis')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('laporan.stok-menipis')) style="font-variation-settings: 'FILL' 1;" @endif>
                warning
            </span>
        </a>

        {{-- Laporan Produk Terlaris --}}
        <a href="{{ route('laporan.produk-terlaris') }}"
           title="Produk Terlaris"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('laporan.produk-terlaris')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('laporan.produk-terlaris')) style="font-variation-settings: 'FILL' 1;" @endif>
                star
            </span>
        </a>

        {{-- Laporan Untung Rugi --}}
        <a href="{{ route('laporan.untung-rugi') }}"
           title="Untung Rugi"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('laporan.untung-rugi')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('laporan.untung-rugi')) style="font-variation-settings: 'FILL' 1;" @endif>
                monitoring
            </span>
        </a>

        <div class="h-px bg-outline-variant/30 my-2 mx-2"></div>

        {{-- Titipan Barang (Konsinyasi) --}}
        <a href="{{ route('titipan.index') }}"
           title="Titipan Barang (Konsinyasi)"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('titipan.*')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('titipan.*')) style="font-variation-settings: 'FILL' 1;" @endif>
                storefront
            </span>
        </a>

        <div class="h-px bg-outline-variant/30 my-2 mx-2"></div>

        {{-- Kasbon / Piutang --}}
        <a href="{{ route('kasbon.index') }}"
           title="Manajemen Kasbon"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('kasbon.*')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('kasbon.*')) style="font-variation-settings: 'FILL' 1;" @endif>
                receipt_long
            </span>
        </a>

        {{-- Pelanggan --}}
        <a href="{{ route('pelanggan.index') }}"
           title="Master Pelanggan"
           class="p-3 rounded-xl transition-colors duration-150
                  {{ request()->routeIs('pelanggan.*')
                      ? 'bg-secondary-container text-on-secondary-container'
                      : 'text-on-surface-variant hover:bg-surface-container-high' }}">
            <span class="material-symbols-outlined"
                  @if(request()->routeIs('pelanggan.*')) style="font-variation-settings: 'FILL' 1;" @endif>
                groups
            </span>
        </a>
    </div>

    {{-- Bottom: settings --}}
    <a href="{{ route('profile.edit') }}"
       title="Profil & Pengaturan"
       class="p-3 rounded-xl transition-colors duration-150 text-on-surface-variant hover:bg-surface-container-high mt-auto">
        <span class="material-symbols-outlined">&#xe8b8;</span>
    </a>
</nav>
