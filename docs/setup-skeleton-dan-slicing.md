# Setup Skeleton — Toko Jadi POS

## 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel toko-jadi-pos
cd toko-jadi-pos
```

## 2. Install Breeze (auth ringan, Blade-based — sesuai AGENTS.md)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
```

Pilih opsi **Blade** waktu prompt muncul (bukan React/Vue/API) — ini sesuai
constraint "Blade-only frontend" di AGENTS.md.

## 3. Setup Tailwind config dari design tokens

Breeze udah include Tailwind by default. Timpa `tailwind.config.js` yang
di-generate Breeze dengan file `tailwind.config.js` yang sudah diterjemahkan
dari `DESIGN.md` (yang sudah kubuatkan sebelumnya).

Install plugin form reset yang disebut di AGENTS.md:
```bash
npm install -D @tailwindcss/forms
```
Tambahkan ke `plugins: []` di `tailwind.config.js` → `require('@tailwindcss/forms')`.

## 4. Load font dari DESIGN.md

Tambahkan di `resources/views/layouts/app.blade.php` (atau `guest.blade.php`),
di dalam `<head>`:

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@600;700&family=Inter:wght@400;500;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
```

## 5. Setup database

```bash
# .env — sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan migrate
```

## 6. Taruh AGENTS.md dan SKILLS.md

Taruh `AGENTS.md` di root project, dan `SKILLS.md` di folder `/skills/`
(sesuai setup Antigravity kamu yang sudah ada).

## 7. Commit skeleton awal

```bash
git init
git add .
git commit -m "chore: skeleton Laravel + Breeze + design tokens"
```

---

# Lanjut: Slicing Halaman Pertama

Urutan slicing yang disarankan (dari yang paling sering dipakai staf):

1. **Login** (bawaan Breeze — biasanya cukup restyle pakai token, gak perlu full re-slice)
2. **Dashboard** — jadikan acuan visual pertama
3. **Master Produk** — modul core, dibutuhkan modul lain
4. **Update Harga (Kasir/Mobile)** — prioritas tinggi karena dipakai harian
5. **Riwayat Harga**, **Konversi Satuan**, **Laporan Tren Harga** — nanti setelah 4 halaman di atas jalan

## Instruksi ke agent Antigravity per halaman

Contoh prompt buat mulai slicing Dashboard:

> Invoke SKILL: laravel-design-tokens dan SKILL: laravel-blade.
> Ambil desain halaman "Dashboard" dari Stitch lewat koneksi MCP.
> Buat `resources/views/dashboard.blade.php` yang extend `layouts.app`.
> Mapping semua warna/font/spacing di desain ke token Tailwind sesuai
> `tailwind.config.js` — jangan hardcode hex code atau ukuran font baru.
> Kalau ada nilai visual yang belum ada tokennya, stop dan tanya dulu
> sebelum lanjut.

Ganti nama halaman & path view sesuai modul yang mau kamu slice. Setelah
Dashboard jadi dan kamu cek hasilnya cocok, halaman berikutnya tinggal
disuruh "samakan gaya dengan dashboard.blade.php yang sudah ada" —
agent punya acuan konkret di codebase, bukan cuma deskripsi abstrak.
