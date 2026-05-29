@extends('layouts.admin')

@section('title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')

@section('content')
<style>
    .page-header {
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 24px;
    }
    .page-back {
        display: inline-flex; align-items: center; justify-content: center;
        width: 34px; height: 34px;
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 8px; color: #6b7280;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }
    .page-back:hover { background: #f3f4f6; color: #111827; }
    .page-back svg { width: 16px; height: 16px; }
    .page-header h2 { font-size: 16px; font-weight: 700; color: #111827; }
    .page-header p { font-size: 12px; color: #6b7280; margin-top: 1px; }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        max-width: 520px;
    }
    .form-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
        background: #fafafa;
        display: flex; align-items: center; gap: 8px;
    }
    .form-card-header svg { width: 16px; height: 16px; color: #3b82f6; }
    .form-card-header span { font-size: 13px; font-weight: 600; color: #374151; }
    .form-card-body { padding: 24px; }

    /* Form field */
    .form-group { margin-bottom: 20px; }
    .form-label {
        display: block;
        font-size: 12px; font-weight: 600; color: #374151;
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .form-label span { color: #ef4444; }
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px; color: #111827;
        background: #fff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
    }
    .form-input.is-error { border-color: #ef4444; }
    .form-error { font-size: 12px; color: #ef4444; margin-top: 4px; }
    .form-hint { font-size: 11px; color: #9ca3af; margin-top: 4px; }

    /* Buttons */
    .form-footer {
        display: flex; align-items: center; gap: 10px;
        padding-top: 8px; border-top: 1px solid #f3f4f6; margin-top: 4px;
    }
    .btn-save {
        display: inline-flex; align-items: center; gap: 6px;
        background: #3b82f6; color: #fff;
        padding: 10px 22px; border-radius: 8px;
        font-size: 13px; font-weight: 600;
        border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(59,130,246,0.3);
    }
    .btn-save:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-save svg { width: 14px; height: 14px; }
    .btn-cancel {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f9fafb; color: #6b7280;
        padding: 10px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 600;
        text-decoration: none;
        border: 1px solid #e5e7eb;
        transition: background 0.2s, color 0.2s;
    }
    .btn-cancel:hover { background: #f3f4f6; color: #374151; }
    .btn-cancel svg { width: 14px; height: 14px; }
</style>

<div class="page-header">
    <a href="{{ route('admin.kategori.index') }}" class="page-back">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h2>{{ isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h2>
        <p>{{ isset($kategori) ? 'Ubah nama kategori yang sudah ada' : 'Buat kategori produk baru' }}</p>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        <span>{{ isset($kategori) ? 'Edit Kategori — ' . $kategori->nama_kategori : 'Kategori Baru' }}</span>
    </div>

    <div class="form-card-body">
        <form action="{{ isset($kategori) ? route('admin.kategori.update', $kategori->id) : route('admin.kategori.store') }}" method="POST">
            @csrf
            @if(isset($kategori))
                @method('PUT')
            @endif

            <div class="form-group">
                <label class="form-label" for="nama_kategori">
                    Nama Kategori <span>*</span>
                </label>
                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    class="form-input {{ $errors->has('nama_kategori') ? 'is-error' : '' }}"
                    value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
                    placeholder="Contoh: Makanan, Minuman, Snack..."
                    required
                    autofocus
                >
                @error('nama_kategori')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="form-hint">Nama kategori akan ditampilkan pada menu produk di halaman kasir.</p>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn-save">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($kategori) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                </button>
                <a href="{{ route('admin.kategori.index') }}" class="btn-cancel">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
