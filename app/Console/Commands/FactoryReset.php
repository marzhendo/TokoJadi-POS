<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

#[Signature('app:factory-reset {--force : Skip konfirmasi manual}')]
#[Description('PERMANEN: Menghapus SELURUH data operasional (transaksi, produk, kategori, dll) kecuali user akun.')]
class FactoryReset extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('====================================================');
        $this->warn('!!! PERINGATAN BAHAYA: FACTORY RESET DIINISIASI  !!!');
        $this->warn('====================================================');
        $this->error('Tindakan ini akan menghapus SEMUA data operasional secara PERMANEN!');
        $this->error('Hanya tabel "users" yang akan dipertahankan.');
        $this->line('');

        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda YAKIN ingin melanjutkan proses ini?')) {
                $this->info('Factory reset dibatalkan. Data Anda aman.');
                return 0;
            }
        }

        $tables = [
            'transaksi_detail',
            'pembayaran_kasbon',
            'transaksi',
            'titipan_penjualan',
            'titipan_barang',
            'produk_satuan_jual',
            'produk',
            'kategori',
            'satuan',
            'pelanggan',
        ];

        $deletedCounts = [];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                DB::table($table)->truncate();
                $deletedCounts[$table] = $count;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Factory reset berhasil diselesaikan!');
        $this->line('Rincian data yang dihapus:');
        
        foreach ($deletedCounts as $table => $count) {
            $this->line("- $table: $count baris dihapus.");
        }

        return 0;
    }
}
