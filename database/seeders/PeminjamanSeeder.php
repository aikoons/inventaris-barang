<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::where('role', 'staff')->first();
        $admin = User::where('role', 'admin')->first();

        $barangs = Barang::take(5)->get();

        $data = [
            [
                'kode_peminjaman'        => 'PMJ-2024-0001',
                'barang_id'              => $barangs[0]->id,
                'user_id'                => $staff->id,
                'nama_peminjam'          => 'Ahmad Fauzi',
                'instansi_peminjam'      => 'Kelas XI IPA 1',
                'jumlah_pinjam'          => 1,
                'tanggal_pinjam'         => now()->subDays(5)->toDateString(),
                'tanggal_kembali_rencana'=> now()->addDays(2)->toDateString(),
                'status'                 => 'dipinjam',
                'keterangan'             => 'Untuk keperluan presentasi tugas',
            ],
            [
                'kode_peminjaman'        => 'PMJ-2024-0002',
                'barang_id'              => $barangs[1]->id,
                'user_id'                => $admin->id,
                'nama_peminjam'          => 'Bapak Hendra',
                'instansi_peminjam'      => 'Guru Matematika',
                'jumlah_pinjam'          => 1,
                'tanggal_pinjam'         => now()->subDays(10)->toDateString(),
                'tanggal_kembali_rencana'=> now()->subDays(3)->toDateString(),
                'status'                 => 'terlambat',
                'keterangan'             => 'Untuk pembelajaran di kelas',
            ],
            [
                'kode_peminjaman'        => 'PMJ-2024-0003',
                'barang_id'              => $barangs[2]->id,
                'user_id'                => $staff->id,
                'nama_peminjam'          => 'Siti Nurhaliza',
                'instansi_peminjam'      => 'Kelas XII IPS 2',
                'jumlah_pinjam'          => 2,
                'tanggal_pinjam'         => now()->subDays(15)->toDateString(),
                'tanggal_kembali_rencana'=> now()->subDays(8)->toDateString(),
                'tanggal_kembali_aktual' => now()->subDays(8)->toDateString(),
                'status'                 => 'dikembalikan',
                'kondisi_kembali'        => 'baik',
                'keterangan'             => 'Untuk praktikum biologi',
                'keterangan_kembali'     => 'Dikembalikan tepat waktu dalam kondisi baik',
            ],
        ];

        foreach ($data as $d) {
            Peminjaman::create($d);
        }
    }
}
