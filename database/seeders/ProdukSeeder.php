<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk_data = [
            // Makanan
            [
                'kategori_id' => 1,
                'sku' => 'MKN001',
                'nama_produk' => 'Nasi Goreng',
                'harga' => 15000,
                'stok' => 50,
                'lokasi' => 'A1',
                'gambar' => null,
            ],
            [
                'kategori_id' => 1,
                'sku' => 'MKN002',
                'nama_produk' => 'Mie Goreng',
                'harga' => 13000,
                'stok' => 45,
                'lokasi' => 'A2',
                'gambar' => null,
            ],
            [
                'kategori_id' => 1,
                'sku' => 'MKN003',
                'nama_produk' => 'Ayam Bakar',
                'harga' => 25000,
                'stok' => 30,
                'lokasi' => 'A3',
                'gambar' => null,
            ],
            // Minuman
            [
                'kategori_id' => 2,
                'sku' => 'MNM001',
                'nama_produk' => 'Es Teh Manis',
                'harga' => 5000,
                'stok' => 100,
                'lokasi' => 'B1',
                'gambar' => null,
            ],
            [
                'kategori_id' => 2,
                'sku' => 'MNM002',
                'nama_produk' => 'Es Jeruk',
                'harga' => 6000,
                'stok' => 80,
                'lokasi' => 'B2',
                'gambar' => null,
            ],
            [
                'kategori_id' => 2,
                'sku' => 'MNM003',
                'nama_produk' => 'Kopi Hitam',
                'harga' => 8000,
                'stok' => 60,
                'lokasi' => 'B3',
                'gambar' => null,
            ],
            // Snack
            [
                'kategori_id' => 3,
                'sku' => 'SNK001',
                'nama_produk' => 'Keripik Kentang',
                'harga' => 12000,
                'stok' => 70,
                'lokasi' => 'C1',
                'gambar' => null,
            ],
            [
                'kategori_id' => 3,
                'sku' => 'SNK002',
                'nama_produk' => 'Goreng Pisang',
                'harga' => 10000,
                'stok' => 55,
                'lokasi' => 'C2',
                'gambar' => null,
            ],
            // Kue
            [
                'kategori_id' => 4,
                'sku' => 'KUE001',
                'nama_produk' => 'Donat',
                'harga' => 3000,
                'stok' => 120,
                'lokasi' => 'D1',
                'gambar' => null,
            ],
            [
                'kategori_id' => 4,
                'sku' => 'KUE002',
                'nama_produk' => 'Bolu Panggang',
                'harga' => 5000,
                'stok' => 40,
                'lokasi' => 'D2',
                'gambar' => null,
            ],
            // Roti
            [
                'kategori_id' => 5,
                'sku' => 'RTI001',
                'nama_produk' => 'Roti Tawar',
                'harga' => 20000,
                'stok' => 35,
                'lokasi' => 'E1',
                'gambar' => null,
            ],
            [
                'kategori_id' => 5,
                'sku' => 'RTI002',
                'nama_produk' => 'Roti Manis',
                'harga' => 18000,
                'stok' => 40,
                'lokasi' => 'E2',
                'gambar' => null,
            ],
        ];

        foreach ($produk_data as $produk) {
            Produk::create($produk);
        }
    }
}
