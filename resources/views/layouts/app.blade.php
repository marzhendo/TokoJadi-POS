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
            <div class="flex flex-col flex-1 ml-20">
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
                <main class="flex-1 overflow-y-auto p-margin-desktop bg-surface">
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
        </div>
        @stack('scripts')
    </body>
</html>
