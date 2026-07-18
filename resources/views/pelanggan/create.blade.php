<x-app-layout>
    <x-slot name="pageTitle">
        Tambah Pelanggan
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-2">
                <a href="{{ route('pelanggan.index') }}" class="text-text-secondary hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <p class="text-sm text-text-secondary">
                    Daftarkan pelanggan baru ke dalam sistem.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                
                <form method="POST" action="{{ route('pelanggan.store') }}" class="p-6 md:p-8 space-y-6">
                    @csrf

                    <div>
                        <label for="nama" class="block text-xs font-bold text-text-secondary uppercase mb-1">Nama Pelanggan <span class="text-margin-danger">*</span></label>
                        <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required autofocus
                            class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        @error('nama')
                            <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kontak" class="block text-xs font-bold text-text-secondary uppercase mb-1">Nomor Kontak (HP/WA)</label>
                        <input id="kontak" type="text" name="kontak" value="{{ old('kontak') }}"
                            class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        @error('kontak')
                            <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-text-secondary uppercase mb-1">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-all">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-margin-danger text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-6 border-t border-outline-variant flex items-center justify-end gap-4">
                        <button type="button" onclick="window.close()" class="text-sm font-bold text-text-secondary hover:text-text-primary transition-colors">
                            Tutup (Jika Pop-up)
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-primary text-primary-on font-bold text-sm rounded-lg hover:bg-primary-container hover:text-primary-on-container transition-colors shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">save</span>
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
