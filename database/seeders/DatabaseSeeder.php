<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Role::truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            'Admin', 'Penulis', 'Editor Naskah', 'Editor Akuisisi', 'Pengelola'
        ];

        foreach ($data as $value) {
            Role::insert([
                'nama_role' => $value
            ]);
        }

        $dataKategori = [
            'Ilmu Kesehatan', 'Pertanian', 'Teknologi Pertanian', 'Peternakan', 'Manajemen Agribisnis', 'Teknologi Informasi', 'Bahasa, Komunikasi, dan Pariwisata', 'Rekayasa', 'Pariwisata'
        ];

        foreach ($dataKategori as $value) {
            Kategori::insert([
                'nama_kategori' => $value
            ]);
        }

        User::factory()->create([
            'name'              => 'Admin',
            'username'          => 'Administrator',
            'email'             => 'admin@test.com',
            'password'          => Hash::make('admintest'),
            'id_role'           => 1,
        ]);
        User::factory()->count(500)->create();
    }
}
