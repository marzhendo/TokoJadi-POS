<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Toko Jadi') }} — Toko Jadi POS</title>

        <!-- Fonts (Google Fonts — sesuai DESIGN.md) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700&family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

        <!-- Material Symbols (icon set dari Stitch) -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-block;
                vertical-align: middle;
                font-size: 20px;
            }
        </style>
    </head>
    <body class="font-body antialiased bg-surface text-on-surface">
        <div class="min-h-screen flex">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Wrapper: topbar + content -->
            <div class="flex flex-col flex-1 sm:ml-20">
                <!-- Top App Bar -->
                <header class="sticky top-0 z-40 flex justify-between items-center w-full h-20 px-margin-desktop bg-surface border-b border-table-border">
                    <div class="flex items-center gap-md">
                        {{-- Page title slot, fallback ke nama app --}}
                        <h1 class="font-headline text-headline-lg text-primary">
                            {{ $pageTitle ?? config('app.name', 'Toko Jadi') }}
                        </h1>
                        @isset($topnav)
                            {{ $topnav }}
                        @endisset
                    </div>
                    <div class="flex items-center gap-lg">
                        <!-- Search (hanya tampil di halaman yang butuh) -->
                        @isset($search)
                            {{ $search }}
                        @endisset

                        <!-- User menu -->
                        <div class="flex items-center gap-md">
                            <!-- ponytail: dropdown auth Breeze dipakai ulang, cukup -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-sm text-on-surface-variant hover:text-on-surface transition-colors">
                                        <span class="material-symbols-outlined">account_circle</span>
                                        <span class="text-body-md hidden md:inline">{{ Auth::user()->name }}</span>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profil') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Keluar') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-margin-desktop bg-surface pb-28 sm:pb-margin-desktop">
                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 px-md py-sm rounded mb-md text-body-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-error-container text-on-error-container px-md py-sm rounded mb-md text-body-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>

            <!-- Bottom Navigation (Mobile Only) -->
            <nav class="flex sm:hidden fixed bottom-0 left-0 w-full z-50 bg-surface-container-lowest border-t border-table-border shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] pb-safe">
                <div class="flex justify-between items-center w-full px-2 py-2">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center flex-1 py-1 transition-colors {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-text-secondary' }}">
                        <span class="material-symbols-outlined mb-1" @if(request()->routeIs('dashboard')) style="font-variation-settings: 'FILL' 1;" @endif>home</span>
                        <span class="text-[10px] font-bold">Dashboard</span>
                    </a>
                    
                    {{-- Kasir --}}
                    <a href="{{ route('transaksi.create') }}" class="flex flex-col items-center flex-1 py-1 transition-colors {{ request()->routeIs('transaksi.create') ? 'text-primary' : 'text-text-secondary' }}">
                        <span class="material-symbols-outlined mb-1" @if(request()->routeIs('transaksi.create')) style="font-variation-settings: 'FILL' 1;" @endif>point_of_sale</span>
                        <span class="text-[10px] font-bold">Kasir</span>
                    </a>
                    
                    {{-- Produk --}}
                    <a href="{{ route('produk.index') }}" class="flex flex-col items-center flex-1 py-1 transition-colors {{ request()->routeIs('produk.*') ? 'text-primary' : 'text-text-secondary' }}">
                        <span class="material-symbols-outlined mb-1" @if(request()->routeIs('produk.*')) style="font-variation-settings: 'FILL' 1;" @endif>inventory_2</span>
                        <span class="text-[10px] font-bold">Produk</span>
                    </a>
                    
                    {{-- Laporan --}}
                    <a href="{{ route('laporan.penjualan') }}" class="flex flex-col items-center flex-1 py-1 transition-colors {{ request()->routeIs('laporan.*') ? 'text-primary' : 'text-text-secondary' }}">
                        <span class="material-symbols-outlined mb-1" @if(request()->routeIs('laporan.*')) style="font-variation-settings: 'FILL' 1;" @endif>analytics</span>
                        <span class="text-[10px] font-bold">Laporan</span>
                    </a>
                    
                    {{-- Lainnya (Toggle Modal/Dropdown) --}}
                    <button onclick="document.getElementById('mobile-more-menu').classList.toggle('hidden')" class="flex flex-col items-center flex-1 py-1 transition-colors text-text-secondary">
                        <span class="material-symbols-outlined mb-1">menu</span>
                        <span class="text-[10px] font-bold">Lainnya</span>
                    </button>
                </div>
            </nav>

            <!-- Mobile More Menu (Bottom Sheet) -->
            <div id="mobile-more-menu" class="hidden sm:hidden fixed inset-0 z-50">
                <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('mobile-more-menu').classList.add('hidden')"></div>
                <div class="absolute bottom-[60px] left-0 w-full bg-surface-container-lowest rounded-t-2xl shadow-lg border-t border-table-border p-md flex flex-col space-y-2 pb-8">
                    <div class="w-12 h-1 bg-table-border rounded-full mx-auto mb-4"></div>
                    
                    <a href="{{ route('transaksi.index') }}" class="flex items-center gap-md p-3 rounded-xl hover:bg-surface-container-high transition-colors {{ request()->routeIs('transaksi.index') ? 'text-primary font-bold' : 'text-on-surface' }}">
                        <span class="material-symbols-outlined" @if(request()->routeIs('transaksi.index')) style="font-variation-settings: 'FILL' 1;" @endif>history</span>
                        Riwayat Transaksi
                    </a>
                    <a href="{{ route('titipan.index') }}" class="flex items-center gap-md p-3 rounded-xl hover:bg-surface-container-high transition-colors {{ request()->routeIs('titipan.*') ? 'text-primary font-bold' : 'text-on-surface' }}">
                        <span class="material-symbols-outlined" @if(request()->routeIs('titipan.*')) style="font-variation-settings: 'FILL' 1;" @endif>storefront</span>
                        Titipan Barang
                    </a>
                    <a href="{{ route('kasbon.index') }}" class="flex items-center gap-md p-3 rounded-xl hover:bg-surface-container-high transition-colors {{ request()->routeIs('kasbon.*') ? 'text-primary font-bold' : 'text-on-surface' }}">
                        <span class="material-symbols-outlined" @if(request()->routeIs('kasbon.*')) style="font-variation-settings: 'FILL' 1;" @endif>receipt_long</span>
                        Manajemen Kasbon
                    </a>
                    <a href="{{ route('pelanggan.index') }}" class="flex items-center gap-md p-3 rounded-xl hover:bg-surface-container-high transition-colors {{ request()->routeIs('pelanggan.*') ? 'text-primary font-bold' : 'text-on-surface' }}">
                        <span class="material-symbols-outlined" @if(request()->routeIs('pelanggan.*')) style="font-variation-settings: 'FILL' 1;" @endif>groups</span>
                        Pelanggan
                    </a>
                    <div class="h-px bg-table-border my-2 mx-4"></div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-md p-3 rounded-xl hover:bg-surface-container-high transition-colors text-on-surface">
                        <span class="material-symbols-outlined">settings</span>
                        Pengaturan
                    </a>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
