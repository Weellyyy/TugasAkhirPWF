@extends('layouts.admin')

@section('title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 max-w-xl">
    <form action="{{ isset($kategori) ? route('admin.kategori.update', $kategori->id) : route('admin.kategori.store') }}" method="POST">
        @csrf
        @if(isset($kategori))
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_kategori">
                Nama Kategori
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_kategori" type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" required>
            @error('nama_kategori')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                Simpan
            </button>
            <a href="{{ route('admin.kategori.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
