<x-app-layout>
    <x-slot name="pageTitle">
        Data Pelanggan
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <p class="text-sm text-text-secondary">
                    Kelola data pelanggan dan pantau aktivitas transaksi mereka.
                </p>
            </div>
            <div>
                <a href="{{ route('pelanggan.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-primary-on font-bold text-sm rounded-lg hover:bg-primary-container hover:text-primary-on-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm mr-2">add</span>
                    Tambah Pelanggan
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
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Nama Pelanggan</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Kontak (HP/WA)</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Alamat Lengkap</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($pelanggan as $p)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-text-primary text-sm">{{ $p->nama }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-text-secondary">
                                        {{ $p->kontak ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-text-secondary max-w-xs truncate" title="{{ $p->alamat }}">
                                        {{ $p->alamat ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('pelanggan.show', $p->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-secondary-container text-secondary-on-container text-xs font-bold rounded-lg hover:brightness-95 transition-all">
                                            <span class="material-symbols-outlined text-[16px] mr-1">menu_book</span>
                                            Buku Kasbon
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">group</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Belum ada pelanggan</h3>
                                        <p class="text-text-secondary text-sm">Data pelanggan masih kosong.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($pelanggan->hasPages())
                    <div class="p-4 border-t border-table-border bg-surface-container-lowest">
                        {{ $pelanggan->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
