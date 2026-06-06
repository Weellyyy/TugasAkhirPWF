<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@pos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'Aktif',
        ]);

        // Jalankan seeder untuk data dummy
        $this->call([
            KategoriSeeder::class,
            ProdukSeeder::class,
            TransaksiSeeder::class,
        ]);
    }
}
