# PRD — Toko Jadi POS

## 1. Latar Belakang

Toko Jadi adalah toko hasil bumi (minyak, tepung, beras, dan sembako lain) milik
keluarga. Saat ini pencatatan stok dan transaksi masih manual (buku/nota), rawan
selisih stok dan susah dilacak untung-ruginya.

## 2. Tujuan

Bikin sistem internal sederhana yang dipakai pemilik toko (Engkong) sehari-hari
untuk:
- Mencatat transaksi penjualan dengan cepat (kasir)
- Mengelola stok otomatis ter-update tiap transaksi
- Melihat laporan penjualan, produk laku, dan untung-rugi
- (Opsional) Mencatat kasbon pelanggan langganan

## 3. Target Pengguna

- **Primary user:** Engkong (non-teknis, perlu UI sangat sederhana, idealnya
  bisa diakses dari HP/tablet di meja kasir)
- **Admin/maintainer:** pemegang sistem (developer), yang setup produk, harga,
  dan lihat laporan dari jauh

## 4. Scope

### In scope (v1)
- Manajemen produk + kategori + satuan (termasuk konversi satuan jual)
- Transaksi penjualan (kasir) — input cepat, auto-hitung total, auto-potong stok
- Riwayat transaksi
- Laporan: penjualan harian/bulanan, produk terlaris, stok menipis, untung-rugi
- Reminder stok minimum

### In scope (v2 — setelah v1 stabil)
- Kasbon pelanggan (hutang + pembayaran cicil)
- Multi-user (kalau ada yang bantu jaga toko)

### Out of scope
- Toko online publik / checkout customer
- Pembayaran online (QRIS, payment gateway) — transaksi tetap cash di v1
- Multi-cabang

## 5. Modul & Build Order

1. **Produk & Satuan** (core — semua modul FK ke sini)
2. **Transaksi Penjualan (Kasir)**
3. **Laporan**
4. **Kasbon Pelanggan** (v2, opsional)

## 6. Roles

- `owner` — akses penuh: produk, transaksi, laporan, setting
- `kasir` — hanya bisa input transaksi & lihat stok (dipakai kalau v2 multi-user)

v1 cukup 1 role (`owner`) dipegang Engkong/developer. Role `kasir` disiapkan
strukturnya tapi belum wajib dipakai.

## 7. Skema Data (ringkas)

```
kategori (id, nama)
satuan (id, nama)
produk (id, kategori_id, nama, satuan_dasar_id, stok_saat_ini,
        stok_minimum, harga_modal_per_satuan_dasar)
produk_satuan_jual (id, produk_id, satuan_id, jumlah_dalam_satuan_dasar,
                     harga_jual)
transaksi (id, tanggal, total_belanja, metode_bayar, pelanggan_id nullable)
transaksi_detail (id, transaksi_id, produk_id, satuan_jual_id, jumlah,
                   harga_satuan, subtotal)

-- v2
pelanggan (id, nama, no_hp, total_hutang)
pembayaran_kasbon (id, pelanggan_id, jumlah_bayar, tanggal)
```

**Relasi kunci:**
```
produk → produk_satuan_jual (1:many) — satu produk bisa punya beberapa
                                         cara jual (liter, jurigen, dst)
transaksi → transaksi_detail (1:many)
transaksi_detail → produk_satuan_jual (belongsTo) — sumber konversi & harga
```

## 8. Logika Bisnis Kunci

- **Konversi satuan:** stok produk selalu disimpan dalam satuan dasar
  (misal liter). Saat transaksi pakai satuan jual lain (misal jurigen),
  sistem kalikan `jumlah` × `jumlah_dalam_satuan_dasar` sebelum potong stok.
- **Potong stok:** terjadi otomatis saat `transaksi_detail` disimpan, dalam
  satu DB transaction bareng `transaksi` (biar konsisten, tidak ada stok
  kepotong tanpa transaksi tercatat atau sebaliknya).
- **Untung-rugi:** `harga_satuan` (dikonversi ke per-satuan-dasar) dikurangi
  `harga_modal_per_satuan_dasar`, dikali jumlah.
- **Stok menipis:** flag produk di laporan kalau `stok_saat_ini <= stok_minimum`.

## 9. Kriteria Sukses v1

- Engkong bisa input 1 transaksi penjualan dalam < 30 detik tanpa bantuan
- Stok tidak pernah minus / tidak konsisten setelah transaksi
- Laporan harian bisa diakses tanpa hitung manual
- Bisa dipakai nyaman dari layar HP (kasir di meja kecil)

## 10. Constraints

- Stack: Laravel 11 · Blade · Tailwind CSS · MySQL (reuse pola dari project
  HMIF ERP — developer sudah familiar)
- Tanpa auth kompleks dulu di v1 (single user/password sederhana), Breeze
  disiapkan biar gampang upgrade ke multi-user di v2
- Mobile-first untuk halaman kasir, desktop-first untuk halaman laporan
