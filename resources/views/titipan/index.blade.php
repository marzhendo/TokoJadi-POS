<x-app-layout>
    <x-slot name="pageTitle">
        Titipan Barang
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <p class="text-sm text-text-secondary">
                    Kelola barang konsinyasi yang dititipkan oleh rekan/saudara.
                </p>
            </div>
            <div>
                <a href="{{ route('titipan.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary text-primary-on font-bold text-sm rounded-lg hover:bg-primary-container hover:text-primary-on-container transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm mr-2">add</span>
                    Terima Titipan Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">

            @if(session('success'))
                <div class="bg-success-container text-margin-success p-4 rounded-xl font-bold flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-error-container text-margin-danger p-4 rounded-xl font-bold flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined">error</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter Section --}}
            <div class="bg-surface border border-outline-variant rounded-xl p-md flex flex-wrap gap-4 items-center">
                <a href="{{ route('titipan.index', ['status' => 'aktif']) }}" 
                   class="px-4 py-2 rounded-lg font-bold text-sm transition-colors {{ $status === 'aktif' ? 'bg-primary-container text-primary-on-container' : 'bg-surface-container-high text-on-surface-variant hover:bg-outline-variant' }}">
                    Sedang Aktif
                </a>
                <a href="{{ route('titipan.index', ['status' => 'selesai']) }}" 
                   class="px-4 py-2 rounded-lg font-bold text-sm transition-colors {{ $status === 'selesai' ? 'bg-primary-container text-primary-on-container' : 'bg-surface-container-high text-on-surface-variant hover:bg-outline-variant' }}">
                    Sudah Selesai
                </a>
                
                <div class="ml-auto">
                    <a href="{{ route('titipan.riwayat') }}" class="inline-flex items-center justify-center px-4 py-2 bg-secondary-container text-secondary-on-container font-bold text-sm rounded-lg hover:brightness-95 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-sm mr-2">history</span>
                        Lihat Riwayat & Bagi Hasil
                    </a>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="overflow-x-auto w-full">
<table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Penitip</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Barang</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Harga Jual</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Komisi Toko</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Sisa Stok</th>
                                @if($status === 'aktif')
                                    <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($titipanBarangs as $t)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-text-primary text-sm">{{ $t->nama_penitip }}</div>
                                        <div class="text-xs text-text-secondary">{{ $t->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-bold text-text-primary">
                                        {{ $t->nama_barang }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-numeric-mono">
                                        Rp {{ number_format($t->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-numeric-mono text-primary font-bold">
                                        Rp {{ number_format($t->komisi_toko, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-sm font-bold font-numeric-mono {{ $t->sisaStok() <= 0 ? 'text-margin-danger' : 'text-text-primary' }}">
                                            {{ floatval($t->sisaStok()) }}
                                        </span>
                                        <span class="text-xs text-text-secondary block">dari {{ floatval($t->jumlah_dititipkan) }}</span>
                                    </td>
                                    
                                    @if($status === 'aktif')
                                    <td class="py-3 px-4 text-center">
                                        <button type="button" 
                                                onclick="openJualModal('{{ $t->id }}', '{{ $t->nama_barang }}', {{ $t->sisaStok() }})"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-primary-container text-primary-on-container text-xs font-bold rounded-lg hover:brightness-95 transition-all">
                                            Catat Penjualan
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $status === 'aktif' ? '6' : '5' }}" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">inventory_2</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Tidak ada data titipan</h3>
                                        <p class="text-text-secondary text-sm">Data titipan barang {{ $status }} kosong.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
</div>
                </div>
                
                @if($titipanBarangs->hasPages())
                    <div class="p-4 border-t border-table-border bg-surface-container-lowest">
                        {{ $titipanBarangs->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Jual Titipan --}}
    <div id="jualModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-inverse-surface/50 backdrop-blur-sm transition-opacity">
        <div class="bg-surface rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden border border-outline-variant">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h3 class="font-headline text-headline-md font-bold text-text-primary">Catat Penjualan Titipan</h3>
                <button type="button" onclick="closeJualModal()" class="text-text-secondary hover:text-text-primary transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="formJualTitipan" method="POST" action="">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Barang</label>
                        <div id="modalNamaBarang" class="text-sm font-bold text-text-primary bg-surface-container p-3 rounded-lg border border-outline-variant"></div>
                    </div>
                    
                    <div>
                        <label for="tanggal" class="block text-xs font-bold text-text-secondary uppercase mb-1">Tanggal Terjual</label>
                        <input type="date" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="jumlah" class="block text-xs font-bold text-text-secondary uppercase mb-1">Jumlah Laku <span id="modalMaxSisa" class="normal-case text-margin-danger font-normal ml-1"></span></label>
                        <input type="number" id="jumlah" name="jumlah" step="0.001" min="0.001" required
                               class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex justify-end gap-3">
                    <button type="button" onclick="closeJualModal()" class="px-4 py-2 text-sm font-bold text-text-secondary hover:text-text-primary transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-primary-on font-bold text-sm rounded-lg hover:bg-primary-container hover:text-primary-on-container transition-colors shadow-sm">
                        Simpan Penjualan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openJualModal(id, namaBarang, sisaStok) {
            document.getElementById('formJualTitipan').action = '/titipan/' + id + '/jual';
            document.getElementById('modalNamaBarang').innerText = namaBarang;
            document.getElementById('modalMaxSisa').innerText = '(Maksimal: ' + sisaStok + ')';
            
            const jumlahInput = document.getElementById('jumlah');
            jumlahInput.max = sisaStok;
            jumlahInput.value = '';
            
            document.getElementById('jualModal').classList.remove('hidden');
        }

        function closeJualModal() {
            document.getElementById('jualModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
