<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['kasir', 'detail.produk'])->orderBy('created_at', 'desc');

        $filter = $request->get('filter', 'semua');
        if ($filter === 'hari') {
            $query->whereDate('created_at', today());
        } elseif ($filter === 'minggu') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter === 'bulan') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        $transaksis = $query->get();

        if ($request->get('export') === 'csv') {
            return $this->exportCsv($transaksis, $filter);
        }

        return view('admin.reports.index', compact('transaksis', 'filter'));
    }

    private function exportCsv($transaksis, $filter)
    {
        $filename = "laporan-penjualan-" . $filter . "-" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($transaksis) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel encoding
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header Row
            fputcsv($file, ['No. Transaksi', 'Tanggal', 'Kasir', 'Detail Produk (Qty x Harga = Subtotal)', 'Metode Pembayaran', 'Total Harga']);

            foreach ($transaksis as $t) {
                // Format detail products in a single readable string
                $details = [];
                foreach ($t->detail as $d) {
                    $prodName = $d->produk->nama_produk ?? 'Produk Dihapus';
                    $details[] = "{$prodName} ({$d->jumlah} x Rp " . number_format($d->harga, 0, ',', '.') . " = Rp " . number_format($d->subtotal, 0, ',', '.') . ")";
                }
                $detailsString = implode(" | ", $details);

                fputcsv($file, [
                    'TRX-' . sprintf('%04d', $t->id),
                    $t->created_at->format('d/m/Y H:i'),
                    $t->kasir->nama ?? 'Unknown',
                    $detailsString,
                    $t->metode_pembayaran,
                    $t->total_harga
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
