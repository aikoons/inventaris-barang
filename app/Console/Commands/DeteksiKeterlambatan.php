<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;

class DeteksiKeterlambatan extends Command
{
    protected $signature   = 'peminjaman:cek-keterlambatan';
    protected $description = 'Menandai peminjaman yang melewati tanggal kembali sebagai terlambat';

    public function handle(): void
    {
        $updated = Peminjaman::where('status', 'dipinjam')
                              ->where('tanggal_kembali_rencana', '<', now()->toDateString())
                              ->update(['status' => 'terlambat']);

        $this->info("Selesai. {$updated} peminjaman ditandai sebagai terlambat.");
    }
}
