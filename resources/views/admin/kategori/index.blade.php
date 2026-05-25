@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <h2 class="text-xl font-semibold text-gray-800">Daftar Kategori</h2>
    <a href="{{ route('admin.kategori.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Tambah Kategori
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
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    ID
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Nama Kategori
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoris as $k)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ $k->id }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap font-medium">{{ $k->nama_kategori }}</p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                    <a href="{{ route('admin.kategori.edit', $k->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                    <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if($kategoris->isEmpty())
            <tr>
                <td colspan="3" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    Belum ada kategori.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
