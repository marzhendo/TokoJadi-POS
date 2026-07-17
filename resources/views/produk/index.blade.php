<x-app-layout>
    <x-slot name="pageTitle">Master Produk</x-slot>

    <x-slot name="search">
        <div class="flex items-center gap-sm px-md py-2 bg-surface-container-lowest border border-outline-variant rounded-full w-80">
            <span class="material-symbols-outlined text-text-secondary" style="font-size:18px">search</span>
            <input type="text"
                   placeholder="Cari nama produk..."
                   class="bg-transparent border-none focus:ring-0 text-body-md w-full placeholder:text-text-secondary p-0">
        </div>
    </x-slot>

    {{-- Breadcrumb + CTA --}}
    <div class="flex justify-between items-end mb-lg">
        <div>
            <nav class="flex items-center gap-sm text-xs text-text-secondary mb-sm">
                <span>Inventory</span>
                <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
                <span class="text-primary font-bold">Master Produk</span>
            </nav>
            <h2 class="font-headline text-headline-lg text-text-primary">Master Produk</h2>
            <p class="text-text-secondary text-body-md mt-1">Kelola data produk, satuan, dan harga modal.</p>
        </div>
        <a href="{{ route('produk.create') }}"
           class="bg-primary text-on-primary px-lg py-3 rounded-xl font-bold flex items-center gap-sm
                  hover:bg-primary-container transition-all active:scale-95 shadow-sm">
            <span class="material-symbols-outlined" style="font-size:20px">add_circle</span>
            Tambah Produk
        </a>
    </div>

    {{-- Toolbar filter --}}
    <div class="bg-surface-container-lowest border border-table-border rounded-xl p-md mb-lg flex justify-between items-center">
        <div class="flex items-center gap-md">
            {{-- Filter kategori --}}
            <div class="relative">
                <select name="kategori_id"
                        onchange="this.form && this.form.submit()"
                        class="appearance-none bg-surface-container-low border border-outline-variant
                               px-md py-2 pr-10 rounded-lg text-body-md font-medium
                               focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary" style="font-size:18px">expand_more</span>
            </div>

            {{-- Filter status stok --}}
            <div class="relative">
                <select name="status"
                        class="appearance-none bg-surface-container-low border border-outline-variant
                               px-md py-2 pr-10 rounded-lg text-body-md font-medium
                               focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="menipis"  {{ request('status') === 'menipis'  ? 'selected' : '' }}>Menipis</option>
                    <option value="habis"    {{ request('status') === 'habis'    ? 'selected' : '' }}>Habis</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary" style="font-size:18px">filter_list</span>
            </div>
        </div>

        <div class="flex items-center gap-sm text-text-secondary text-body-md">
            <span>Total: <strong class="text-text-primary">{{ $produk->total() }}</strong> produk</span>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-surface-container-lowest border border-table-border rounded-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-surface-container-low border-b border-table-border">
                <tr>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase">Nama Produk</th>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase">Kategori</th>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase">Satuan Dasar</th>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-right">Harga Modal</th>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-right">Stok</th>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-center">Status</th>
                    <th class="px-lg py-md font-mono text-label-caps text-text-secondary uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-table-border">
                @forelse($produk as $p)
                    @php $status = $p->statusStok(); @endphp
                    <tr class="hover:bg-primary/5 transition-colors">

                        {{-- Nama --}}
                        <td class="px-lg py-md">
                            <div class="flex items-center gap-md">
                                <div class="w-10 h-10 bg-surface-container rounded-lg flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-primary" style="font-size:20px">inventory_2</span>
                                </div>
                                <div>
                                    <p class="font-body text-table-data font-semibold text-text-primary">{{ $p->nama }}</p>
                                    <p class="text-xs text-text-secondary">ID: {{ $p->id }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-lg py-md">
                            <span class="px-sm py-1 rounded-lg bg-secondary-container text-on-secondary-container font-mono text-label-caps">
                                {{ strtoupper($p->kategori->nama) }}
                            </span>
                        </td>

                        {{-- Satuan Dasar --}}
                        <td class="px-lg py-md">
                            <span class="font-body text-body-md font-medium text-text-primary">{{ $p->satuanDasar->nama }}</span>
                        </td>

                        {{-- Harga Modal --}}
                        <td class="px-lg py-md text-right">
                            <span class="font-mono text-numeric-mono text-text-secondary">
                                Rp {{ number_format($p->harga_modal_per_satuan_dasar, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Stok --}}
                        <td class="px-lg py-md text-right">
                            <span class="font-mono text-numeric-mono font-bold
                                         {{ $status === 'habis' ? 'text-margin-danger' : ($status === 'menipis' ? 'text-margin-warning' : 'text-text-primary') }}">
                                {{ number_format($p->stok_saat_ini, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-text-secondary ml-1">{{ $p->satuanDasar->nama }}</span>
                        </td>

                        {{-- Status badge — token sesuai SKILL: laravel-design-tokens --}}
                        <td class="px-lg py-md text-center">
                            @if($status === 'tersedia')
                                <span class="px-sm py-1 rounded-lg bg-margin-success/15 text-margin-success font-mono text-label-caps border border-margin-success/30">
                                    TERSEDIA
                                </span>
                            @elseif($status === 'menipis')
                                <span class="px-sm py-1 rounded-lg bg-margin-warning/15 text-margin-warning font-mono text-label-caps border border-margin-warning/30">
                                    MENIPIS
                                </span>
                            @else
                                <span class="px-sm py-1 rounded-lg bg-margin-danger/15 text-margin-danger font-mono text-label-caps border border-margin-danger/30">
                                    HABIS
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-lg py-md">
                            <div class="flex items-center justify-center gap-sm">
                                <a href="{{ route('produk.edit', $p) }}"
                                   title="Edit"
                                   class="p-2 text-text-secondary hover:text-primary hover:bg-primary-container/10 rounded-lg transition-all">
                                    <span class="material-symbols-outlined" style="font-size:20px">edit_note</span>
                                </a>
                                <form method="POST" action="{{ route('produk.destroy', $p) }}"
                                      onsubmit="return confirm('Hapus produk {{ addslashes($p->nama) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            title="Hapus"
                                            class="p-2 text-text-secondary hover:text-margin-danger hover:bg-margin-danger/10 rounded-lg transition-all">
                                        <span class="material-symbols-outlined" style="font-size:20px">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-lg py-xl text-center text-text-secondary text-body-md">
                            <span class="material-symbols-outlined block mx-auto mb-sm" style="font-size:48px;opacity:.3">inventory_2</span>
                            Belum ada produk. <a href="{{ route('produk.create') }}" class="text-primary font-bold hover:underline">Tambah sekarang</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($produk->hasPages())
            <div class="px-lg py-md bg-surface-container-low border-t border-table-border flex justify-between items-center">
                <div class="text-xs text-text-secondary">
                    Menampilkan {{ $produk->firstItem() }}–{{ $produk->lastItem() }} dari {{ $produk->total() }} produk
                </div>
                <div class="text-body-md">
                    {{ $produk->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- FAB tambah produk --}}
    <a href="{{ route('produk.create') }}"
       id="fab-tambah-produk"
       class="fixed bottom-lg right-lg bg-primary text-on-primary rounded-full w-14 h-14
              flex items-center justify-center shadow-lg hover:scale-110 active:scale-95
              transition-all duration-150 z-50 group">
        <span class="material-symbols-outlined group-hover:rotate-90 transition-transform duration-200" style="font-size:28px">add</span>
    </a>

</x-app-layout>
