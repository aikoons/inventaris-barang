<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin utama
        User::create([
            'name'         => 'admin',
            'nama_lengkap' => 'Administrator Sekolah',
            'email'        => 'admin@inventaris.sch.id',
            'telepon'      => '081234567890',
            'role'         => 'admin',
            'is_active'    => true,
            'password'     => Hash::make('password'),
        ]);

        // Staff 1
        User::create([
            'name'         => 'budi.staff',
            'nama_lengkap' => 'Budi Santoso',
            'email'        => 'budi@inventaris.sch.id',
            'telepon'      => '082345678901',
            'role'         => 'staff',
            'is_active'    => true,
            'password'     => Hash::make('password'),
        ]);

        // Staff 2
        User::create([
            'name'         => 'sari.staff',
            'nama_lengkap' => 'Sari Dewi',
            'email'        => 'sari@inventaris.sch.id',
            'telepon'      => '083456789012',
            'role'         => 'staff',
            'is_active'    => true,
            'password'     => Hash::make('password'),
        ]);
    }
}
