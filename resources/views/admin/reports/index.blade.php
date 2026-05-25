@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Detail Transaksi Penjualan</h2>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <p class="text-sm text-gray-600">Menampilkan semua data transaksi penjualan.</p>
    </div>
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail Produk (Qty x Harga)</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $t)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm whitespace-nowrap">
                    {{ $t->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $t->kasir->nama ?? 'Unknown' }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($t->detail as $d)
                        <li>
                            {{ $d->produk->nama_produk ?? 'Produk Dihapus' }} 
                            <span class="text-gray-500">({{ $d->jumlah }} x Rp {{ number_format($d->harga, 0, ',', '.') }})</span>
                            = Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $t->metode_pembayaran }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-900">
                    Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
            @if($transaksis->isEmpty())
            <tr>
                <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    Belum ada transaksi.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
