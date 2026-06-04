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
        $produkHampirHabis = Produk::where('stok', '<=', 30)->get();

        // Data tambahan untuk dashboard baru
        $rataRataPesanan = $jumlahPenjualan > 0 ? $pendapatan / $jumlahPenjualan : 0;
        $pelangganBaru = \App\Models\User::count(); // asumsikan user sebagai pelanggan

        // Data grafik tren penjualan 7 hari terakhir (Data Aktual)
        $trenPenjualan = [];
        $hariMap = ['Sun' => 'Min', 'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab'];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = Transaksi::whereDate('created_at', $date->format('Y-m-d'))->sum('total_harga');
            $trenPenjualan[] = [
                'hari' => $hariMap[$date->format('D')],
                'total' => $total
            ];
        }

        // Produk Terlaris (Data Aktual)
        $terlarisStats = \App\Models\DetailTransaksi::selectRaw('produk_id, SUM(jumlah) as terjual, SUM(subtotal) as pendapatan')
            ->groupBy('produk_id')
            ->orderByDesc('terjual')
            ->take(3)
            ->get();

        $produkTerlaris = [];
        foreach ($terlarisStats as $stat) {
            $produk = Produk::with('kategori')->find($stat->produk_id);
            if ($produk) {
                $produk->terjual = $stat->terjual;
                $produk->pendapatan = $stat->pendapatan;
                $produkTerlaris[] = $produk;
            }
        }

        return view('admin.dashboard', compact(
            'pendapatan', 'jumlahPenjualan', 'produkHampirHabis', 
            'rataRataPesanan', 'pelangganBaru', 'trenPenjualan', 'produkTerlaris'
        ));
    }
}
