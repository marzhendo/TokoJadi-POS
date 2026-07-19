<x-app-layout>
    <x-slot name="pageTitle">
        Buku Kasbon: {{ $pelanggan->nama }}
    </x-slot>

    <x-slot name="topnav">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 w-full">
            <div class="flex items-center gap-2">
                <a href="{{ route('pelanggan.index') }}" class="text-text-secondary hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <p class="text-sm text-text-secondary">
                    Kontak: {{ $pelanggan->kontak ?? '-' }} | Alamat: {{ $pelanggan->alamat ?? '-' }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-md">
            
            {{-- Data Table --}}
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="overflow-x-auto w-full">
<table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-lowest border-b border-table-border">
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider">Tgl Transaksi</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Total Belanja</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Sudah Dibayar</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-right">Sisa Hutang</th>
                                <th class="py-3 px-4 text-xs font-bold text-text-secondary uppercase tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            @forelse($transaksiKasbon as $t)
                                <tr class="hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-text-primary text-sm">{{ $t->tanggal->format('d M Y H:i') }}</div>
                                        <div class="text-xs text-text-secondary">Nota: #{{ $t->id }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-numeric-mono">
                                        Rp {{ number_format($t->total_belanja, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-sm text-right font-numeric-mono text-margin-success">
                                        Rp {{ number_format($t->pembayaranKasbon->sum('jumlah_bayar'), 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-sm font-bold font-numeric-mono {{ $t->sisaKasbon() > 0 ? 'text-margin-danger' : 'text-text-secondary' }}">
                                            Rp {{ number_format($t->sisaKasbon(), 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($t->isLunas())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-success-container text-margin-success">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-error-container text-margin-danger">
                                                Belum Lunas
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <span class="material-symbols-outlined text-5xl block mb-3 text-primary opacity-20">receipt_long</span>
                                        <h3 class="text-lg font-bold text-text-primary mb-1">Belum ada riwayat kasbon</h3>
                                        <p class="text-text-secondary text-sm">Pelanggan ini belum pernah melakukan transaksi kasbon.</p>
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
</x-app-layout>
