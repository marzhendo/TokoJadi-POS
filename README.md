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

| Komponen | Teknologi | Keterangan |
| --- | --- | --- |
| **Backend** | ![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white) | Framework MVC yang andal dan aman (Laravel 11, PHP). |
| **Frontend** | ![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white) | Blade Templates dipadukan dengan Tailwind CSS untuk antarmuka responsif dan konsisten. |
| **Interaktivitas UI** | ![Alpine.js](https://img.shields.io/badge/alpinejs-%238BC0D0.svg?style=for-the-badge&logo=alpine.js&logoColor=white) | Menangani modal, notifikasi, dan interaksi form dinamis secara ringan. |
| **Database** | ![MySQL](https://img.shields.io/badge/mysql-%234479A1.svg?style=for-the-badge&logo=mysql&logoColor=white) | Menjaga integritas relasi dan transaksi dengan perlindungan *Foreign Key*. |

---
<br>

Copyright &copy; 2026 marzhendo. All rights reserved.
