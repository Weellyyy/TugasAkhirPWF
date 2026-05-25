<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaksi;

class ReportController extends Controller
{
    public function index()
    {
        // Load transaksi with kasir, detail, and the related produk
        $transaksis = Transaksi::with(['kasir', 'detail.produk'])->orderBy('created_at', 'desc')->get();
        return view('admin.reports.index', compact('transaksis'));
    }
}
