<x-app-layout>
    <x-slot name="pageTitle">Kasir</x-slot>

<div class="max-w-4xl mx-auto pb-24 md:pb-12">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="font-headline-lg text-headline-lg text-text-primary font-bold">Kasir</h1>
        <p class="text-text-secondary font-body-md text-sm">Catat transaksi penjualan baru.</p>
    </div>

    <!-- Error Messages (Validation) -->
    @if ($errors->any())
        <div class="mb-6 bg-error-container text-on-error-container p-4 rounded-xl border border-error/20 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="font-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Kiri: Form Input -->
        <div class="bg-surface-white border border-table-border p-4 md:p-6 rounded-xl shadow-sm h-fit">
            <h2 class="text-headline-md font-bold text-primary mb-4">Pilih Item</h2>
            
            <div class="space-y-4">
                <!-- Produk -->
                <div>
                    <label class="block text-xs font-bold text-text-secondary mb-1">PRODUK</label>
                    <select id="select-produk" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary">
                        <option value="">Pilih Produk...</option>
                        @foreach($produkList as $produk)
                            <option value="{{ $produk->id }}">{{ $produk->nama }} (Stok: {{ floatval($produk->stok_saat_ini) }} {{ $produk->satuanDasar->nama }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Satuan Jual -->
                <div>
                    <label class="block text-xs font-bold text-text-secondary mb-1">SATUAN JUAL</label>
                    <select id="select-satuan-jual" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary disabled:opacity-50" disabled>
                        <option value="">Pilih Produk Dulu...</option>
                    </select>
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-xs font-bold text-text-secondary mb-1">JUMLAH BELI</label>
                    <div class="relative">
                        <input type="number" inputmode="decimal" id="input-jumlah" min="0.001" step="0.001" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg font-numeric-mono text-lg focus:ring-1 focus:ring-primary focus:border-primary disabled:opacity-50" disabled placeholder="0">
                        <span id="label-satuan" class="absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary text-sm font-bold"></span>
                    </div>
                </div>

                <button type="button" id="btn-tambah" class="w-full bg-primary-container text-on-primary-container py-3 rounded-lg font-bold flex items-center justify-center gap-2 hover:opacity-90 transition-all disabled:opacity-50 mt-6" disabled>
                    <span class="material-symbols-outlined" data-icon="add_shopping_cart">add_shopping_cart</span> Tambah ke Keranjang
                </button>
            </div>
        </div>

        <!-- Kanan: Keranjang -->
        <div class="bg-surface-white border border-table-border flex flex-col rounded-xl shadow-sm h-[500px] md:h-auto">
            <div class="p-4 border-b border-table-border bg-surface-container-low rounded-t-xl">
                <h2 class="text-headline-md font-bold text-primary">Keranjang Belanja</h2>
            </div>
            
            <div id="cart-list" class="flex-grow overflow-y-auto p-4 space-y-3">
                <!-- Cart items will be rendered here via JS -->
                <div id="empty-state" class="h-full flex flex-col items-center justify-center text-text-secondary">
                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50" data-icon="shopping_basket">shopping_basket</span>
                    <p class="text-sm">Keranjang kosong</p>
                </div>
            </div>

            <!-- Form Submit (Total & Actions) -->
            <form id="pos-form" action="{{ route('transaksi.store') }}" method="POST" class="p-4 border-t border-table-border bg-surface-container-lowest rounded-b-xl">
                @csrf
                <input type="hidden" name="metode_bayar" value="cash">
                
                <div id="hidden-inputs-container"></div>
                
                <div class="flex justify-between items-end mb-4">
                    <span class="text-sm font-bold text-text-secondary">TOTAL</span>
                    <span id="display-total" class="font-display-price text-3xl font-bold text-primary">Rp 0</span>
                </div>

                <button type="submit" id="btn-submit" class="w-full bg-primary text-on-primary py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-2 hover:opacity-90 active:scale-95 transition-all disabled:opacity-50" disabled>
                    <span class="material-symbols-outlined" data-icon="payments">payments</span> Simpan Transaksi
                </button>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inject data produk dari backend
    const produkList = @json($produkList);
    
    // JS State
    let cart = [];
    let selectedProduk = null;
    let selectedSatuanJual = null;

    // DOM Elements
    const selectProduk = document.getElementById('select-produk');
    const selectSatuanJual = document.getElementById('select-satuan-jual');
    const inputJumlah = document.getElementById('input-jumlah');
    const labelSatuan = document.getElementById('label-satuan');
    const btnTambah = document.getElementById('btn-tambah');
    const cartList = document.getElementById('cart-list');
    const emptyState = document.getElementById('empty-state');
    const displayTotal = document.getElementById('display-total');
    const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
    const btnSubmit = document.getElementById('btn-submit');

    // Utility: Format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    // Lookup harga dari produkList berdasarkan satuan_jual_id
    function getHargaInfo(satuanJualId) {
        for (const prod of produkList) {
            for (const sj of prod.satuan_jual) {
                if (sj.id == satuanJualId) {
                    return {
                        harga_jual: parseFloat(sj.harga_jual),
                        produk_nama: prod.nama,
                        satuan_nama: prod.satuan_dasar.nama, // Asumsi simpel
                        // idealnya kita tau nama satuan jualnya, tapi di produkList, relasi ke satuan jual table satuan belum di eager load
                        // Wait, did we load satuan on satuan_jual? No, let's just use "satuan" or we need to eager load it.
                        // I will patch the controller to include satuanJual.satuan to be safe, but if not we can just show "Unit" or fetch from elsewhere.
                    };
                }
            }
        }
        return { harga_jual: 0, produk_nama: 'Unknown', satuan_nama: 'Unit' };
    }

    // Render Cart
    function renderCart() {
        if (cart.length === 0) {
            emptyState.style.display = 'flex';
            cartList.querySelectorAll('.cart-item').forEach(el => el.remove());
            displayTotal.innerText = 'Rp 0';
            btnSubmit.disabled = true;
            hiddenInputsContainer.innerHTML = '';
            return;
        }

        emptyState.style.display = 'none';
        cartList.querySelectorAll('.cart-item').forEach(el => el.remove());
        hiddenInputsContainer.innerHTML = '';
        
        let total = 0;

        cart.forEach((item, index) => {
            const info = getHargaInfo(item.satuan_jual_id);
            const subtotal = info.harga_jual * item.jumlah;
            total += subtotal;

            // Generate HTML for cart item
            const itemEl = document.createElement('div');
            itemEl.className = 'cart-item flex justify-between items-center p-3 border border-outline-variant rounded-lg bg-surface';
            itemEl.innerHTML = `
                <div>
                    <h3 class="font-bold text-sm text-text-primary">${info.produk_nama}</h3>
                    <p class="text-xs text-text-secondary">${parseFloat(item.jumlah)} x ${formatRupiah(info.harga_jual)}</p>
                </div>
                <div class="text-right flex flex-col items-end gap-1">
                    <span class="font-bold text-sm font-numeric-mono text-primary">${formatRupiah(subtotal)}</span>
                    <button type="button" class="text-error text-xs hover:underline flex items-center" onclick="window.removeCartItem(${index})">
                        Hapus
                    </button>
                </div>
            `;
            cartList.appendChild(itemEl);

            // Generate Hidden Inputs
            hiddenInputsContainer.innerHTML += `
                <input type="hidden" name="items[${index}][satuan_jual_id]" value="${item.satuan_jual_id}">
                <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
            `;
        });

        displayTotal.innerText = formatRupiah(total);
        btnSubmit.disabled = false;
    }

    // Expose remove to global so onclick can call it
    window.removeCartItem = function(index) {
        cart.splice(index, 1);
        renderCart();
    };

    // Event: Produk Berubah
    selectProduk.addEventListener('change', function() {
        const pId = this.value;
        selectSatuanJual.innerHTML = '<option value="">Pilih Satuan...</option>';
        selectSatuanJual.disabled = true;
        inputJumlah.disabled = true;
        btnTambah.disabled = true;
        labelSatuan.innerText = '';
        inputJumlah.value = '';

        if (!pId) return;

        selectedProduk = produkList.find(p => p.id == pId);
        if (selectedProduk && selectedProduk.satuan_jual.length > 0) {
            selectSatuanJual.disabled = false;
            selectedProduk.satuan_jual.forEach(sj => {
                const opt = document.createElement('option');
                opt.value = sj.id;
                // Since we don't have the satuan name eagerly loaded, we will just show harga and konversi
                opt.text = `Rp ${parseFloat(sj.harga_jual).toLocaleString('id-ID')} (Isi ${parseFloat(sj.jumlah_dalam_satuan_dasar)} ${selectedProduk.satuan_dasar?.nama || 'Unit'})`;
                selectSatuanJual.appendChild(opt);
            });
        } else {
            selectSatuanJual.innerHTML = '<option value="">Tidak ada satuan jual aktif</option>';
        }
    });

    // Event: Satuan Jual Berubah
    selectSatuanJual.addEventListener('change', function() {
        if (this.value) {
            inputJumlah.disabled = false;
            btnTambah.disabled = false;
            // Focus on quantity input
            inputJumlah.focus();
        } else {
            inputJumlah.disabled = true;
            btnTambah.disabled = true;
        }
    });

    // Event: Tambah ke Keranjang
    btnTambah.addEventListener('click', function() {
        const sId = selectSatuanJual.value;
        const qty = parseFloat(inputJumlah.value);

        if (!sId || !qty || qty <= 0) {
            alert('Pilih satuan jual dan masukkan jumlah yang valid.');
            return;
        }

        // Cek jika sudah ada di keranjang, bisa ditambahkan
        const existingIdx = cart.findIndex(c => c.satuan_jual_id == sId);
        if (existingIdx >= 0) {
            cart[existingIdx].jumlah += qty;
        } else {
            cart.push({
                satuan_jual_id: sId,
                jumlah: qty
            });
        }

        renderCart();
        
        // Reset form
        selectProduk.value = '';
        selectProduk.dispatchEvent(new Event('change'));
    });

    // Allow Enter key in input-jumlah to add
    inputJumlah.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Mencegah form submit!
            btnTambah.click();
        }
    });
    
    // Disable form submit on enter everywhere except submit button
    document.getElementById('pos-form').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && e.target.id !== 'btn-submit') {
            e.preventDefault();
        }
    });

    // Init Old Data
    const oldItems = @json(old('items', []));
    if (oldItems && Object.keys(oldItems).length > 0) {
        // oldItems might be an object mapping indices to values depending on how PHP serializes it, so we iterate
        Object.values(oldItems).forEach(item => {
            cart.push({
                satuan_jual_id: item.satuan_jual_id,
                jumlah: parseFloat(item.jumlah)
            });
        });
        renderCart();
    }
});
</script>
</x-app-layout>
