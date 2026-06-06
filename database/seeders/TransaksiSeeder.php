<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama atau buat jika tidak ada
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Admin POS',
                'email' => 'admin@pos.com',
                'password' => bcrypt('password'),
            ]);
        }

        $produk = Produk::all();

        // Buat 10 transaksi dummy
        for ($i = 1; $i <= 10; $i++) {
            $metode_pembayaran = $i % 2 == 0 ? 'Qris' : 'Cash';
            
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'total_harga' => 0,
                'metode_pembayaran' => $metode_pembayaran,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);

            // Tambah 2-4 detail transaksi per transaksi
            $jumlah_detail = rand(2, 4);
            $total_harga = 0;

            for ($j = 0; $j < $jumlah_detail; $j++) {
                $random_produk = $produk->random();
                $jumlah = rand(1, 5);
                $harga = $random_produk->harga;
                $subtotal = $jumlah * $harga;
                $total_harga += $subtotal;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $random_produk->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update total harga transaksi
            $transaksi->update(['total_harga' => $total_harga]);
        }
    }
}
