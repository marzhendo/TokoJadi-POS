<x-app-layout>
    <x-slot name="pageTitle">Tambah Produk Baru</x-slot>

    {{-- Breadcrumb + Header --}}
    <div class="flex justify-between items-end mb-lg">
        <div>
            <nav class="flex items-center gap-sm text-xs text-text-secondary mb-sm">
                <a href="{{ route('produk.index') }}" class="hover:underline">Inventory</a>
                <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
                <a href="{{ route('produk.index') }}" class="hover:underline">Master Produk</a>
                <span class="material-symbols-outlined" style="font-size:14px">chevron_right</span>
                <span class="text-primary font-bold">Tambah Produk Baru</span>
            </nav>
            <h2 class="font-headline text-headline-lg text-text-primary">Tambah Produk Baru</h2>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-table-border shadow-sm max-w-4xl mb-xl">
        <form action="{{ route('produk.store') }}" method="POST" class="p-8">
            @csrf

            @if($errors->any())
                <div class="bg-error-container text-on-error-container p-md rounded-xl mb-lg">
                    <ul class="list-disc pl-5 text-body-md font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-2 gap-6 mb-8">
                {{-- NAMA PRODUK --}}
                <div class="col-span-2">
                    <label class="block font-mono text-label-caps text-text-secondary mb-2">NAMA PRODUK</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                           placeholder="Contoh: Cabe Rawit Merah" />
                </div>
                
                {{-- KATEGORI --}}
                <div>
                    <label class="block font-mono text-label-caps text-text-secondary mb-2">KATEGORI</label>
                    <select name="kategori_id" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- SATUAN DASAR --}}
                <div>
                    <label class="block font-mono text-label-caps text-text-secondary mb-2">SATUAN DASAR</label>
                    <select name="satuan_dasar_id" id="satuan_dasar_select" required class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Pilih Satuan</option>
                        @foreach($satuanList as $s)
                            <option value="{{ $s->id }}" data-nama="{{ $s->nama }}" {{ old('satuan_dasar_id') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- STOK SAAT INI --}}
                <div>
                    <label class="block font-mono text-label-caps text-text-secondary mb-2">STOK SAAT INI <span class="text-xs normal-case font-normal">(hanya saat buat produk)</span></label>
                    <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', 0) }}" step="0.001" min="0" required
                           class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all" />
                </div>
                
                {{-- STOK MINIMUM --}}
                <div>
                    <label class="block font-mono text-label-caps text-text-secondary mb-2">STOK MINIMUM PENGINGAT</label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" step="0.001" min="0" required
                           class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all" />
                </div>

                {{-- HARGA MODAL --}}
                <div class="col-span-2">
                    <label class="block font-mono text-label-caps text-text-secondary mb-2">HARGA MODAL (PER SATUAN DASAR)</label>
                    <div class="flex items-center">
                        <span class="bg-surface-container border border-outline-variant border-r-0 rounded-l-xl px-4 py-3 font-bold text-text-secondary">Rp</span>
                        <input type="number" name="harga_modal_per_satuan_dasar" value="{{ old('harga_modal_per_satuan_dasar', 0) }}" min="0" required
                               class="w-full px-4 py-3 bg-surface-container-low border border-outline-variant rounded-r-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all" />
                    </div>
                </div>
            </div>

            {{-- SATUAN JUAL & KONVERSI (REPEATER) --}}
            <div class="mb-8 p-6 bg-surface-container-low rounded-2xl border border-dashed border-outline-variant">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">sync_alt</span>
                        <h4 class="font-bold text-sm text-text-primary">Satuan Jual & Konversi</h4>
                    </div>
                    <button type="button" id="btn-add-satuan-jual" class="text-primary text-xs font-bold flex items-center gap-1 hover:underline">
                        <span class="material-symbols-outlined text-sm">add</span> Tambah Baris
                    </button>
                </div>

                <div id="satuan-jual-container" class="space-y-3">
                    {{-- Rows will be populated by JS --}}
                </div>
            </div>

            <div class="flex gap-4 justify-end mt-8 border-t border-table-border pt-6">
                <a href="{{ route('produk.index') }}" class="px-6 py-3 border border-outline-variant text-text-primary font-bold rounded-xl hover:bg-surface-container transition-colors">Batal</a>
                <button type="submit" class="px-8 py-3 bg-primary text-on-primary font-bold rounded-xl hover:bg-primary-container shadow-sm active:scale-95 transition-all">Simpan Produk</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('satuan-jual-container');
            const btnAdd = document.getElementById('btn-add-satuan-jual');
            const satuanDasarSelect = document.getElementById('satuan_dasar_select');
            
            const satuanList = @json($satuanList);
            const oldData = @json(old('satuan_jual', []));
            
            let rowCount = 0;

            function updateSatuanDasarLabels() {
                const selectedOption = satuanDasarSelect.options[satuanDasarSelect.selectedIndex];
                const namaSatuan = selectedOption && selectedOption.value !== "" ? selectedOption.getAttribute('data-nama') : 'Satuan Dasar';
                
                document.querySelectorAll('.label-satuan-dasar').forEach(el => {
                    el.value = namaSatuan;
                });
            }

            function createRow(data = null) {
                const index = rowCount++;
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-3 items-end bg-surface-white p-3 rounded-xl border border-table-border';
                
                let optionsHtml = '<option value="">Pilih</option>';
                satuanList.forEach(s => {
                    const selected = (data && data.satuan_id == s.id) ? 'selected' : '';
                    optionsHtml += `<option value="${s.id}" ${selected}>${s.nama}</option>`;
                });

                row.innerHTML = `
                    <div class="col-span-3">
                        <p class="text-[10px] font-bold text-text-secondary mb-1">SATUAN JUAL</p>
                        <select name="satuan_jual[${index}][satuan_id]" required class="w-full px-3 py-2 bg-surface-container-low border border-outline-variant rounded-lg text-xs font-medium focus:ring-1 focus:ring-primary focus:border-primary">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-span-1 flex justify-center pb-2">
                        <span class="material-symbols-outlined text-text-secondary text-sm">=</span>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-bold text-text-secondary mb-1">JML KONVERSI</p>
                        <input type="number" name="satuan_jual[${index}][jumlah_dalam_satuan_dasar]" value="${data ? data.jumlah_dalam_satuan_dasar : '1'}" step="0.001" min="0.001" required class="w-full px-3 py-2 bg-white border border-outline-variant rounded-lg text-xs font-medium focus:ring-1 focus:ring-primary focus:border-primary" />
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-bold text-text-secondary mb-1">SATUAN DS</p>
                        <input type="text" readonly class="label-satuan-dasar w-full px-3 py-2 bg-surface-container border border-outline-variant rounded-lg text-xs font-medium cursor-not-allowed text-text-secondary" value="-" />
                    </div>
                    <div class="col-span-3">
                        <p class="text-[10px] font-bold text-text-secondary mb-1">HARGA JUAL</p>
                        <div class="flex items-center">
                            <span class="bg-surface-container border border-outline-variant border-r-0 rounded-l-lg px-2 py-2 font-bold text-xs text-text-secondary">Rp</span>
                            <input type="number" name="satuan_jual[${index}][harga_jual]" value="${data ? data.harga_jual : '0'}" min="0" required class="w-full px-3 py-2 bg-white border border-outline-variant rounded-r-lg text-xs font-medium focus:ring-1 focus:ring-primary focus:border-primary" />
                        </div>
                    </div>
                    <div class="col-span-1 flex justify-center pb-2">
                        <button type="button" class="btn-remove-row text-margin-danger hover:bg-error-container p-1 rounded transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                `;

                row.querySelector('.btn-remove-row').addEventListener('click', function() {
                    row.remove();
                });

                container.appendChild(row);
                updateSatuanDasarLabels();
            }

            btnAdd.addEventListener('click', () => createRow());
            satuanDasarSelect.addEventListener('change', updateSatuanDasarLabels);

            // Init rows
            if (oldData && oldData.length > 0) {
                oldData.forEach(item => createRow(item));
            } else {
                createRow(); // Create at least 1 empty row
            }
        });
    </script>
    @endpush
</x-app-layout>
