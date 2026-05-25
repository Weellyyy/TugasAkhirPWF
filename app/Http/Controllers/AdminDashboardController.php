<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaksi;
use App\Models\Produk;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendapatan = Transaksi::sum('total_harga');
        $jumlahPenjualan = Transaksi::count();
        $produkHampirHabis = Produk::where('stok', '<=', 5)->get();

        return view('admin.dashboard', compact('pendapatan', 'jumlahPenjualan', 'produkHampirHabis'));
    }
}
