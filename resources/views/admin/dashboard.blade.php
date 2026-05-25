@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Pendapatan -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Jumlah Penjualan -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 border-l-4 border-l-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Penjualan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $jumlahPenjualan }} Transaksi</p>
            </div>
        </div>
    </div>
</div>

<!-- Produk Hampir Habis -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800">Produk Hampir Habis (Stok &le; 5)</h2>
    </div>
    <div class="p-0">
        @if($produkHampirHabis->count() > 0)
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm">
                    <th class="px-6 py-3 font-medium">Nama Produk</th>
                    <th class="px-6 py-3 font-medium">Kategori</th>
                    <th class="px-6 py-3 font-medium">Sisa Stok</th>
                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($produkHampirHabis as $produk)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $produk->nama_produk }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ $produk->stok }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.produk.edit', $produk->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Update Stok</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-6 text-center text-gray-500">
            Semua stok produk dalam keadaan aman.
        </div>
        @endif
    </div>
</div>
@endsection
