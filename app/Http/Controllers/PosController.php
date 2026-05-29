<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $produks = Produk::with('kategori')->where('stok', '>', 0)->get();
        $kategoris = Kategori::all();
        
        $receipt = null;
        if (session('show_receipt_id')) {
            $receipt = Transaksi::with(['kasir', 'detail.produk'])->find(session('show_receipt_id'));
        }
        
        return view('pos.index', compact('produks', 'kategoris', 'receipt'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'cart' => 'required|string',
            'metode_pembayaran' => 'required|in:Cash,Qris'
        ]);

        $cart = json_decode($request->cart, true);
        
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            foreach ($cart as $item) {
                $totalHarga += ($item['harga'] * $item['qty']);
            }

            $transaksi = Transaksi::create([
                'user_id' => auth()->id(),
                'total_harga' => $totalHarga,
                'metode_pembayaran' => $request->metode_pembayaran
            ]);

            foreach ($cart as $item) {
                $produk = Produk::findOrFail($item['id']);
                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi!");
                }
                
                $produk->stok -= $item['qty'];
                $produk->save();

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $produk->id,
                    'jumlah' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['harga'] * $item['qty']
                ]);
            }

            DB::commit();
            return redirect()->route('pos')
                ->with('success', 'Transaksi berhasil disimpan!')
                ->with('show_receipt_id', $transaksi->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
}
