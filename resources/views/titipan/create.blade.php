<x-app-layout>
    <x-slot name="pageTitle">
        Terima Titipan Baru
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-2">
                <a href="{{ route('titipan.index') }}" class="text-text-secondary hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <p class="text-sm text-text-secondary">
                    Daftarkan barang konsinyasi baru yang masuk ke toko.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                
                <form method="POST" action="{{ route('titipan.store') }}" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama Penitip --}}
                        <div class="md:col-span-2">
                            <label for="nama_penitip" class="block text-xs font-bold text-text-secondary uppercase mb-1">Nama Penitip</label>
                            <input id="nama_penitip" type="text" name="nama_penitip" value="{{ old('nama_penitip') }}" required autofocus
                                class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            @error('nama_penitip')
                                <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Barang --}}
                        <div class="md:col-span-2">
                            <label for="nama_barang" class="block text-xs font-bold text-text-secondary uppercase mb-1">Nama Barang Titipan</label>
                            <input id="nama_barang" type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                                class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            @error('nama_barang')
                                <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Harga Jual --}}
                        <div>
                            <label for="harga_jual" class="block text-xs font-bold text-text-secondary uppercase mb-1">Harga Jual (per item)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-text-secondary text-sm">Rp</span>
                                </div>
                                <input id="harga_jual" type="number" name="harga_jual" value="{{ old('harga_jual') }}" min="0" step="0.01" required
                                    class="w-full pl-10 px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all font-numeric-mono">
                            </div>
                            <p class="text-text-secondary text-xs mt-1">Harga jual ke konsumen.</p>
                            @error('harga_jual')
                                <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Komisi Toko --}}
                        <div>
                            <label for="komisi_toko" class="block text-xs font-bold text-primary uppercase mb-1">Komisi Toko (per item)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-primary text-sm font-bold">Rp</span>
                                </div>
                                <input id="komisi_toko" type="number" name="komisi_toko" value="{{ old('komisi_toko') }}" min="0" step="0.01" required
                                    class="w-full pl-10 px-4 py-2.5 bg-surface-container-lowest border border-primary rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all font-numeric-mono">
                            </div>
                            <p class="text-text-secondary text-xs mt-1">Keuntungan pasti untuk toko setiap 1 barang laku.</p>
                            @error('komisi_toko')
                                <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jumlah Dititipkan --}}
                        <div class="md:col-span-2">
                            <label for="jumlah_dititipkan" class="block text-xs font-bold text-text-secondary uppercase mb-1">Jumlah Awal Dititipkan</label>
                            <input id="jumlah_dititipkan" type="number" name="jumlah_dititipkan" value="{{ old('jumlah_dititipkan') }}" min="0.001" step="0.001" required
                                class="w-full md:w-1/2 px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all font-numeric-mono">
                            @error('jumlah_dititipkan')
                                <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-outline-variant flex items-center justify-end gap-4">
                        <a href="{{ route('titipan.index') }}" class="text-sm font-bold text-text-secondary hover:text-text-primary transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-primary text-primary-on font-bold text-sm rounded-lg hover:bg-primary-container hover:text-primary-on-container transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan Titipan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
