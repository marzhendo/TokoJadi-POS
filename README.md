# TokoJadi POS (Point of Sales)

TokoJadi POS adalah sistem Point of Sales (Kasir) modern berbasis web yang dirancang secara khusus untuk memenuhi kompleksitas operasional toko ritel/kelontong menengah ke bawah. Sistem ini tidak hanya menangani penjualan standar, melainkan didesain sedemikian rupa untuk menyelesaikan kendala nyata di lapangan seperti konversi multi-satuan (misal: Dus ke Renceng ke Pcs), pencatatan barang titipan (konsinyasi), serta manajemen piutang/kasbon pelanggan yang terintegrasi secara mulus.

---

## Mengapa Proyek Ini Dibuat?

Sistem kasir pada umumnya (di pasaran) terlalu kaku atau terlalu mahal untuk operasional toko kelontong harian yang memiliki alur bisnis unik. TokoJadi POS dibuat secara spesifik dengan alasan berikut:
1. **Masalah Multi-Satuan:** Toko sering kulakan dalam bentuk Dus, tapi menjualnya dalam bentuk Pcs atau Pack. Sistem ini secara otomatis memecah dan mengkalkulasi harga modal hingga satuan terkecil agar margin keuntungan akurat 100%.
2. **Barang Konsinyasi (Titipan):** Sulit membedakan uang hasil penjualan barang milik sendiri dengan barang titipan *supplier*. Sistem memisahkan secara jelas mana omzet toko dan mana utang konsinyasi.
3. **Manajemen Kasbon:** Realitas di lapangan, banyak pelanggan sekitar yang berhutang (kasbon). Pencatatan di buku tulis sangat rentan hilang dan sulit direkap. Sistem ini mendigitalisasi buku kasbon lengkap dengan riwayat pelunasan sebagian/penuh.
4. **Analisis Margin Cerdas:** Memberikan peringatan (*warning*) apabila margin suatu barang terlalu tipis (di bawah standar persentase yang aman), sehingga mencegah kebocoran profit secara tersembunyi.

---

## Arsitektur Sistem & Modul

Sistem ini dibangun dengan pendekatan _Monolithic Architecture_ berbasis arsitektur MVC (Model-View-Controller) bawaan framework untuk memastikan kecepatan *deployment* dan kemudahan *maintenance*.

Berikut adalah visualisasi bagaimana setiap modul saling berinteraksi secara harmonis di balik layar:

```mermaid
graph TD
    %% Definisi Aktor
    User([Kasir / Admin])
    
    %% Kumpulan Modul Master
    subgraph Master Data
        M_Prod[Master Produk]
        M_Ktg[Kategori]
        M_Sat[Satuan & Multi-Harga]
        M_Plg[Pelanggan]
    end

    %% Kumpulan Modul Inti Operasional
    subgraph Core Operations
        Kasir[Modul Kasir / Transaksi]
        Kasbon[Manajemen Kasbon]
        Titipan[Konsinyasi / Titipan]
    end
    
    %% Kumpulan Analitik
    subgraph Reporting & Analytics
        Dashboard[Dasbor Utama]
        Lap[Laporan Keuangan & Stok]
    end
    
    %% Relasi Master Data
    M_Prod -->|Dikelompokkan oleh| M_Ktg
    M_Prod -->|Dipecah menjadi| M_Sat
    
    %% Relasi Aksi User
    User -->|Melakukan Penjualan| Kasir
    User -->|Mengelola| Master Data
    User -->|Mencatat Barang| Titipan
    
    %% Alur Transaksi
    M_Prod -.->|Diakses oleh| Kasir
    M_Sat -.->|Harga & Stok| Kasir
    
    %% Kasbon Flow
    Kasir -->|Jika Metode = Kasbon| Kasbon
    M_Plg -.->|Menunggak di| Kasbon
    
    %% Titipan Flow
    Titipan -->|Integrasi Penjualan| Kasir
    
    %% Alur Laporan
    Kasir ===>|Menghasilkan Data| Lap
    Kasbon ===>|Menghasilkan Data| Lap
    Titipan ===>|Menghasilkan Data| Lap
    
    %% Dashboard Aggregation
    Lap -.->|Ringkasan KPI| Dashboard
    M_Prod -.->|Peringatan Margin| Dashboard
```

---

## Tech Stack (Tumpukan Teknologi)

Proyek ini ditenagai oleh perpaduan teknologi modern dan tangguh:

*   **Backend:** [Laravel 11](https://laravel.com/) (PHP) – *Framework MVC yang andal dan aman.*
*   **Frontend:** [Blade Templates](https://laravel.com/docs/blade) dipadukan dengan [Tailwind CSS](https://tailwindcss.com/) untuk antarmuka yang sangat responsif, cantik, dan konsisten (menggunakan desain sistem token).
*   **Interaktivitas UI:** [Alpine.js](https://alpinejs.dev/) – *Untuk menangani modal, notifikasi, dan interaksi form dinamis tanpa memberatkan DOM.*
*   **Database:** Relational Database (MySQL / SQLite) yang dirancang kuat menjaga integritas transaksi dengan perlindungan *Foreign Key*.
*   **Iconography:** Google Material Symbols.

---
<br>

&copy; copyright marzhendo
