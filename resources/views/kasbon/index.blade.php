<x-app-layout>
    <x-slot name="pageTitle">
        Manajemen Kasbon
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div>
                <p class="text-sm text-text-secondary">
                    Daftar piutang aktif yang belum lunas. Catat pembayaran di sini.
                </p>
            </div>
            <div>
                <a href="{{ route('pelanggan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-secondary-container text-secondary-on-container font-bold text-sm rounded-lg hover:brightness-95 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm mr-2">groups</span>
                    Master Pelanggan
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

            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="overflow-x-auto w-full">
<table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Pelanggan</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Tgl Nota (Umur)</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Total Belanja</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Sisa Tagihan</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($transaksiKasbon as $t)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-text-primary text-sm">{{ $t->pelanggan->nama }}</div>
                                        <a href="{{ route('pelanggan.show', $t->pelanggan_id) }}" class="text-xs text-primary hover:underline">Lihat Buku</a>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-text-primary">{{ $t->tanggal->format('d M Y') }}</div>
                                        <div class="text-xs text-margin-danger">{{ $t->tanggal->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-numeric-mono">
                                        Rp {{ number_format($t->total_belanja, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-sm font-bold text-margin-danger font-numeric-mono block">
                                            Rp {{ number_format($t->sisaKasbon(), 0, ',', '.') }}
                                        </span>
                                        @if($t->pembayaranKasbon->count() > 0)
                                            <span class="text-xs text-text-secondary block">Dari {{ $t->pembayaranKasbon->count() }}x bayar</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button type="button" 
                                                onclick="openBayarModal('{{ $t->id }}', '{{ $t->pelanggan->nama }}', {{ $t->sisaKasbon() }})"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-primary-container text-primary-on-container text-xs font-bold rounded-lg hover:brightness-95 transition-all">
                                            <span class="material-symbols-outlined text-[16px] mr-1">payments</span>
                                            Terima Cicilan
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">check_circle</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Bersih!</h3>
                                        <p class="text-text-secondary text-sm">Tidak ada piutang atau kasbon pelanggan yang aktif saat ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
</div>
                </div>
                
                @if($transaksiKasbon->hasPages())
                    <div class="p-4 border-t border-table-border bg-surface-container-lowest">
                        {{ $transaksiKasbon->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal Bayar Kasbon --}}
    <div id="bayarModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-inverse-surface/50 backdrop-blur-sm transition-opacity">
        <div class="bg-surface rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden border border-outline-variant">
            <div class="px-6 py-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-lowest">
                <h3 class="font-headline text-headline-md font-bold text-text-primary">Catat Pembayaran Kasbon</h3>
                <button type="button" onclick="closeBayarModal()" class="text-text-secondary hover:text-text-primary transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="formBayarKasbon" method="POST" action="">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-1">Pelanggan</label>
                        <div id="modalNamaPelanggan" class="text-sm font-bold text-text-primary bg-surface-container p-3 rounded-lg border border-outline-variant"></div>
                    </div>
                    
                    <div>
                        <label for="tanggal_bayar" class="block text-xs font-bold text-text-secondary uppercase mb-1">Tanggal Pembayaran</label>
                        <input type="date" id="tanggal_bayar" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label for="jumlah_bayar" class="block text-xs font-bold text-text-secondary uppercase mb-1">Jumlah Bayar <span id="modalMaxSisa" class="normal-case text-margin-danger font-normal ml-1"></span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-text-secondary text-sm">Rp</span>
                            </div>
                            <input type="number" id="jumlah_bayar" name="jumlah_bayar" step="0.01" min="0.01" required
                                class="w-full pl-10 px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary font-numeric-mono">
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex justify-end gap-3">
                    <button type="button" onclick="closeBayarModal()" class="px-4 py-2 text-sm font-bold text-text-secondary hover:text-text-primary transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-primary-on font-bold text-sm rounded-lg hover:bg-primary-container hover:text-primary-on-container transition-colors shadow-sm">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiahJs(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        function openBayarModal(id, namaPelanggan, sisaKasbon) {
            document.getElementById('formBayarKasbon').action = '/kasbon/' + id + '/bayar';
            document.getElementById('modalNamaPelanggan').innerText = namaPelanggan;
            document.getElementById('modalMaxSisa').innerText = '(Maksimal: Rp ' + formatRupiahJs(sisaKasbon) + ')';
            
            const jumlahInput = document.getElementById('jumlah_bayar');
            jumlahInput.max = sisaKasbon;
            jumlahInput.value = ''; // Biarkan kosong agar kasir ketik
            
            document.getElementById('bayarModal').classList.remove('hidden');
        }

        function closeBayarModal() {
            document.getElementById('bayarModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
