@extends('layouts.admin')

@section('title', 'Manajemen Stok Barang')

@section('content')
<div class="print-header hidden mb-6 text-center">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Stok Barang (POS Master)</h1>
    <p class="text-sm text-gray-500">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<div class="mb-4 flex justify-between items-center no-print">
    <h2 class="text-xl font-semibold text-gray-800">Daftar Barang</h2>
    <div class="flex gap-2">
        <button onclick="window.print()" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-4 rounded shadow flex items-center gap-1.5 transition cursor-pointer text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Barang
        </button>
        <a href="{{ route('admin.produk.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded shadow transition text-sm">
            + Tambah Barang
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-hidden products-table-container">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $p)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    @if($p->gambar)
                        <img src="{{ asset('storage/' . $p->gambar) }}" alt="gambar" class="w-12 h-12 rounded object-cover">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-500 text-xs">No Img</div>
                    @endif
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-700">{{ $p->sku }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium text-gray-900">{{ $p->nama_produk }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-700">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-700">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-700">
                    <span class="{{ $p->stok <= 5 ? 'text-red-600 font-bold' : '' }}">{{ $p->stok }}</span>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-700">{{ $p->lokasi }}</td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center whitespace-nowrap">
                    <a href="{{ route('admin.produk.edit', $p->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                    <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if($produks->isEmpty())
            <tr>
                <td colspan="8" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    Belum ada barang.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<style>
    @media print {
        body * {
            visibility: hidden !important;
        }
        .print-header, .print-header *,
        .products-table-container, .products-table-container *,
        table, table * {
            visibility: visible !important;
        }
        .print-header {
            display: block !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
        }
        .products-table-container {
            position: absolute !important;
            top: 80px !important;
            left: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }
        .no-print, th:last-child, td:last-child {
            display: none !important;
        }
        body {
            background-color: white !important;
            color: black !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
            background-color: white !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #cbd5e1 !important;
        }
    }
    .print-header {
        display: none;
    }
</style>
@endsection
