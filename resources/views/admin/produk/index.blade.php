@extends('layouts.admin')

@section('title', 'Manajemen Stok Barang')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold text-gray-800">Daftar Barang</h2>
    <a href="{{ route('admin.produk.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah Barang
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-hidden">
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
@endsection
