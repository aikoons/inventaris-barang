<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Elektronik',       'kode_kategori' => 'ELK', 'deskripsi' => 'Peralatan elektronik seperti komputer, laptop, proyektor',          'warna' => '#4F46E5'],
            ['nama_kategori' => 'Furnitur',          'kode_kategori' => 'FRN', 'deskripsi' => 'Meja, kursi, lemari, rak dan perabot ruangan lainnya',              'warna' => '#0EA5E9'],
            ['nama_kategori' => 'Alat Laboratorium', 'kode_kategori' => 'LAB', 'deskripsi' => 'Peralatan laboratorium IPA, kimia, fisika, dan biologi',            'warna' => '#10B981'],
            ['nama_kategori' => 'Alat Olahraga',     'kode_kategori' => 'OLR', 'deskripsi' => 'Peralatan olahraga seperti bola, net, matras, dll',                 'warna' => '#F59E0B'],
            ['nama_kategori' => 'Buku & Referensi',  'kode_kategori' => 'BKU', 'deskripsi' => 'Buku pelajaran, ensiklopedia, kamus, dan referensi lainnya',       'warna' => '#8B5CF6'],
            ['nama_kategori' => 'Alat Tulis',        'kode_kategori' => 'ATK', 'deskripsi' => 'Alat tulis kantor, kertas, tinta, dan perlengkapan administrasi',   'warna' => '#EF4444'],
            ['nama_kategori' => 'Alat Kebersihan',   'kode_kategori' => 'KBR', 'deskripsi' => 'Peralatan kebersihan sekolah seperti sapu, pel, kemoceng, dll',    'warna' => '#06B6D4'],
            ['nama_kategori' => 'Lain-lain',         'kode_kategori' => 'LNL', 'deskripsi' => 'Barang-barang yang tidak masuk kategori di atas',                   'warna' => '#6B7280'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create($k);
        }
    }
}
