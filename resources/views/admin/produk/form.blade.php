@extends('layouts.admin')

@section('title', isset($produk) ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 max-w-2xl">
    <form action="{{ isset($produk) ? route('admin.produk.update', $produk->id) : route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($produk))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="kategori_id">Kategori</label>
            <select name="kategori_id" id="kategori_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ (old('kategori_id', $produk->kategori_id ?? '')) == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_produk">Nama Produk</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_produk" type="text" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
            @error('nama_produk') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="harga">Harga (Rp)</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="harga" type="number" name="harga" value="{{ old('harga', $produk->harga ?? '') }}" required>
                @error('harga') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="stok">Stok</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="stok" type="number" name="stok" value="{{ old('stok', $produk->stok ?? '') }}" required>
                @error('stok') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="gambar">Gambar (Opsional)</label>
            <input type="file" name="gambar" id="gambar" class="w-full text-gray-700" accept="image/*">
            @if(isset($produk) && $produk->gambar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $produk->gambar) }}" class="h-20 rounded" alt="gambar produk">
                </div>
            @endif
            @error('gambar') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Simpan
            </button>
            <a href="{{ route('admin.produk.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
