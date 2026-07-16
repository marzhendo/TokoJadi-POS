# SKILLS.md — Toko Jadi POS Laravel Skills

Kumpulan skill cards yang digunakan agent saat mengerjakan modul tertentu.
Invoke secara eksplisit dengan menyebut nama skill, atau agent akan memilih
otomatis berdasarkan konteks task.

---

## SKILL: laravel-migration

**Invoke when:** membuat atau mengedit migration file

**Rules:**
- Gunakan `$table->foreignId('x_id')->constrained()->cascadeOnDelete()` untuk FK standar
- Gunakan `->nullOnDelete()` jika FK boleh null (contoh: `pelanggan_id` di transaksi)
- `decimal`, bukan `float`, untuk semua kolom uang dan stok
  (`harga_modal`, `harga_jual`, `stok_saat_ini`, `jumlah_dalam_satuan_dasar`) —
  hindari floating point error di angka uang/stok
- Selalu tambah `$table->timestamps()`
- Nama tabel: plural snake_case (`produk_satuan_jual`, `transaksi_detail`)
- Nama kolom enum bahasa Indonesia, konsisten dengan PRD
  (contoh: `metode_bayar` = `cash|kasbon`)

**Example:**
```php
// BENAR — kolom uang/stok pakai decimal
$table->decimal('harga_jual', 12, 2);
$table->decimal('stok_saat_ini', 12, 3); // 3 desimal buat satuan liter/kg

// BENAR — FK nullable dengan nullOnDelete
$table->foreignId('pelanggan_id')->nullable()->constrained()->nullOnDelete();

// SALAH — jangan pakai float untuk uang/stok
$table->float('harga_jual');
```

---

## SKILL: laravel-model

**Invoke when:** membuat atau mengedit Eloquent model

**Rules:**
- Gunakan `$fillable` bukan `$guarded = []`
- Definisikan relasi hanya yang dibutuhkan task saat ini (YAGNI)
- Cast kolom uang/stok: `protected $casts = ['harga_jual' => 'decimal:2', ...]`
- Jangan tambah accessor/mutator kecuali diminta
- Logic konversi satuan & potong stok TIDAK boleh ditaruh di model sebagai
  accessor tersembunyi — taruh eksplisit di controller/service biar gampang
  ditrace pas ada selisih stok (ponytail: traceability > magic)

**Relasi yang sudah didefinisikan di PRD:**
```php
// Produk
public function kategori(): BelongsTo
public function satuanDasar(): BelongsTo        // FK: satuan_dasar_id → satuan
public function satuanJual(): HasMany           // FK: produk_id → produk_satuan_jual

// ProdukSatuanJual
public function produk(): BelongsTo
public function satuan(): BelongsTo

// Transaksi
public function detail(): HasMany               // FK: transaksi_id
public function pelanggan(): BelongsTo          // nullable, v2

// TransaksiDetail
public function produkSatuanJual(): BelongsTo
```

---

## SKILL: laravel-controller

**Invoke when:** membuat controller baru

**Rules:**
- Selalu gunakan resource controller (`--resource` flag)
- Gunakan Form Request untuk validasi
- Method `index()` di laporan harus support filter query string (tanggal,
  kategori) via `request()->query()`
- Gunakan route model binding di `show`, `edit`, `update`, `destroy`
- Return ke Blade view dengan `view('module.action', compact('var'))`
- Flash message dengan `session()->flash('success', '...')` setelah CUD

**Boilerplate transaksi (kasir) — WAJIB pakai DB transaction:**
```php
public function store(StoreTransaksiRequest $request): RedirectResponse
{
    $data = $request->validated();

    DB::transaction(function () use ($data) {
        $transaksi = Transaksi::create([
            'tanggal' => now(),
            'metode_bayar' => $data['metode_bayar'],
            'pelanggan_id' => $data['pelanggan_id'] ?? null,
            'total_belanja' => 0, // di-update di akhir loop
        ]);

        $total = 0;
        foreach ($data['items'] as $item) {
            $satuanJual = ProdukSatuanJual::findOrFail($item['satuan_jual_id']);
            $subtotal = $satuanJual->harga_jual * $item['jumlah'];

            $transaksi->detail()->create([
                'produk_id' => $satuanJual->produk_id,
                'satuan_jual_id' => $satuanJual->id,
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $satuanJual->harga_jual,
                'subtotal' => $subtotal,
            ]);

            // potong stok dalam satuan dasar
            $satuanJual->produk()->decrement(
                'stok_saat_ini',
                $item['jumlah'] * $satuanJual->jumlah_dalam_satuan_dasar
            );

            $total += $subtotal;
        }

        $transaksi->update(['total_belanja' => $total]);
    });

    return redirect()->route('transaksi.index')
        ->with('success', 'Transaksi tersimpan.');
}
```

---

## SKILL: laravel-blade

**Invoke when:** membuat atau mengedit Blade view

**Rules:**
- Semua view extend `layouts.app`
- Gunakan Tailwind utility class — jangan inline style
- Form selalu punya `@csrf`
- Method spoofing untuk PUT/DELETE: `@method('PUT')`
- Halaman **kasir** wajib mobile-first: target tap area besar, minim
  scroll horizontal, input angka pakai `inputmode="decimal"`
- Halaman **laporan** boleh desktop-first (tabel lebar, chart)
- Komponen Blade (`x-`) hanya jika dipakai 3+ tempat (ponytail: YAGNI)
- Warna, font, radius, spacing WAJIB pakai token dari `tailwind.config.js`
  (lihat SKILL: laravel-design-tokens) — jangan hex code baru

**Struktur view per modul:**
```
resources/views/
├── produk/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── kasir/
│   └── index.blade.php   ← halaman transaksi, mobile-first
└── laporan/
    ├── penjualan.blade.php
    └── stok-menipis.blade.php
```

**Flash message snippet (taruh di layouts/app.blade.php):**
```blade
@if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
```

---

## SKILL: laravel-design-tokens

**Invoke when:** slicing halaman hasil export Stitch (MCP) ke Blade, atau
menambah/mengedit elemen visual apapun (warna, font, radius, spacing)

**Rules:**
- `tailwind.config.js` di root project adalah satu-satunya sumber kebenaran
  untuk warna, font, radius, dan spacing — token diterjemahkan dari
  `DESIGN.md` (export Stitch)
- Saat slicing markup dari Stitch (via MCP), JANGAN copy hex code atau
  inline style mentah — mapping tiap nilai ke class Tailwind yang sesuai:
  - Warna primary/aksen → `bg-primary`, `text-primary`, dst (bukan `bg-[#2E7D32]`)
  - Badge margin → `text-margin-success` / `text-margin-warning` / `text-margin-danger`
  - Border tabel → `border-table-border`
  - Font headline/harga → `font-headline text-display-price`
  - Font body/tabel → `font-body text-table-data`
  - Font data teknis (SKU, konversi) → `font-mono text-numeric-mono`
- Kalau ada nilai visual di desain Stitch yang BELUM ada tokennya di
  `tailwind.config.js`, STOP — jangan hardcode. Tanya dulu apakah perlu
  ditambah token baru ke config.
- Radius standar (button/input/card): `rounded` (4px). Badge/chip pakai
  `rounded-lg` (8px) — sesuai `DESIGN.md`.
- Elevation: card pakai `border` 1px (`border-table-border`) + `bg-white`,
  TANPA shadow. Modal/FAB baru pakai shadow tipis.
- FAB "Update Harga Cepat": lingkaran hijau (`bg-primary`) di kanan bawah,
  ikon putih, hanya di halaman kasir/mobile.

---

## SKILL: laravel-query-filter

**Invoke when:** membuat fitur filter di halaman laporan

**Rules:**
- Filter via query string, bukan session atau POST
- Gunakan Eloquent query chaining dengan `when()`
- Jangan bikin filter class/scope kecuali diminta

**Pattern:**
```php
// LaporanController index()
$transaksi = Transaksi::query()
    ->when(request('dari'), fn($q, $v) => $q->whereDate('tanggal', '>=', $v))
    ->when(request('sampai'), fn($q, $v) => $q->whereDate('tanggal', '<=', $v))
    ->with('detail.produkSatuanJual.produk')
    ->latest('tanggal')
    ->paginate(20);
```

**Di Blade, pertahankan filter saat paginasi:**
```blade
{{ $transaksi->withQueryString()->links() }}
```

---

## SKILL: laravel-stok-konversi

**Invoke when:** ada logic yang menyangkut konversi satuan atau potong stok

**Rules:**
- Stok di tabel `produk` SELALU dalam satuan dasar. Tidak ada pengecualian.
- Konversi: `jumlah_input × jumlah_dalam_satuan_dasar = jumlah_satuan_dasar`
- Potong stok dan create transaksi_detail HARUS dalam `DB::transaction()`
  yang sama (lihat SKILL: laravel-controller)
- Validasi di Form Request: jumlah yang diinput tidak boleh bikin stok
  jadi negatif — cek `$produk->stok_saat_ini >= $jumlahDalamSatuanDasar`
  SEBELUM `DB::transaction()` dijalankan
- Saat tampilkan stok ke Engkong di halaman produk, convert balik ke
  satuan yang familiar buat dia (jangan cuma tampilin angka liter mentah
  kalau dia biasa mikir per jurigen) — tapi ini tampilan saja, data
  tetap disimpan dalam satuan dasar