@extends('layouts.admin')

@section('title', 'Manajemen Stok Barang')

@section('content')
<style>
    .page-header {
        display: flex; flex-direction: column; gap: 4px;
        margin-bottom: 24px;
    }
    .page-header-top {
        display: flex; align-items: center; justify-content: space-between;
    }
    .page-header h2 { font-size: 18px; font-weight: 700; color: #111827; }
    .page-header p  { font-size: 13px; color: #6b7280; margin-top: 2px; }

    .header-actions {
        display: flex; align-items: center; gap: 12px;
    }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; color: #374151;
        padding: 9px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 600;
        border: 1px solid #d1d5db; cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-outline:hover { background: #f9fafb; border-color: #9ca3af; }
    .btn-outline svg { width: 15px; height: 15px; }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 6px;
        background: #0056d2; color: #fff; /* Updated to a vibrant blue to match image */
        padding: 9px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 600;
        border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(0, 86, 210, 0.3);
        text-decoration: none;
    }
    .btn-primary:hover { background: #0043a8; transform: translateY(-1px); }
    .btn-primary svg { width: 15px; height: 15px; }

    /* Alert */
    .alert-success {
        display: flex; align-items: center; gap: 10px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        color: #15803d; border-radius: 10px;
        padding: 12px 16px; margin-bottom: 20px;
        font-size: 13px; font-weight: 500;
    }
    .alert-success svg { width: 16px; height: 16px; flex-shrink: 0; }

    /* Card */
    .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px; }
    
    /* Table */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f9fafb; }
    .data-table thead th {
        padding: 12px 20px;
        font-size: 11px; font-weight: 600; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.5px;
        text-align: left; border-bottom: 1px solid #f3f4f6;
    }
    .data-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background 0.15s; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }
    .data-table td { padding: 16px 20px; font-size: 13px; color: #111827; vertical-align: middle; }

    .prod-img {
        width: 48px; height: 48px; border-radius: 8px; object-fit: cover;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .prod-img-placeholder {
        width: 48px; height: 48px; border-radius: 8px; background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        color: #9ca3af; font-size: 10px; border: 1px solid #e5e7eb;
    }

    .sku-text { font-family: monospace; color: #4b5563; font-size: 12px; }
    .prod-name { font-weight: 700; color: #1f2937; }
    
    .badge-kategori {
        display: inline-block; padding: 4px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
    }
    .badge-kategori.makanan { background: #6ee7b7; color: #064e3b; } /* Green based on screenshot */
    .badge-kategori.minuman { background: #bfdbfe; color: #1e3a8a; } /* Blue based on screenshot */
    .badge-kategori.default { background: #f3f4f6; color: #374151; }

    .price-text { font-weight: 600; color: #374151; }
    
    .badge-stok {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 4px 10px; border-radius: 20px;
        font-size: 12px; font-weight: 700;
    }
    .badge-stok.safe { background: #d1fae5; color: #065f46; } /* Light green */
    .badge-stok.low { background: #fecdd3; color: #9f1239; } /* Light pink/red */

    .lokasi-text { color: #4b5563; font-size: 13px; }

    .action-group { display: flex; align-items: center; gap: 8px; }
    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        background: #eff6ff; color: #2563eb; padding: 6px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s;
    }
    .btn-edit:hover { background: #dbeafe; }
    .btn-edit svg { width: 13px; height: 13px; }
    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s;
    }
    .btn-delete:hover { background: #fee2e2; }
    .btn-delete svg { width: 13px; height: 13px; }

    /* Pagination area */
    .pagination-area {
        display: flex; justify-content: space-between; align-items: center;
        padding: 16px 20px; border-top: 1px solid #f3f4f6; background: #fff;
    }
    .pagination-info { font-size: 13px; color: #6b7280; }
    
    /* Summary Cards */
    .summary-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 24px;
    }
    .summary-card {
        background: #fff; border-radius: 12px; padding: 20px;
        display: flex; align-items: center; gap: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .summary-card.sku { border-left: 4px solid #3b82f6; }
    .summary-card.aman { border-left: 4px solid #10b981; }
    .summary-card.menipis { border-left: 4px solid #ef4444; }

    .summary-icon {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .summary-card.sku .summary-icon { background: #eff6ff; color: #3b82f6; }
    .summary-card.aman .summary-icon { background: #ecfdf5; color: #10b981; }
    .summary-card.menipis .summary-icon { background: #fef2f2; color: #ef4444; }
    .summary-icon svg { width: 24px; height: 24px; }

    .summary-label { font-size: 11px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-value { font-size: 20px; font-weight: 800; color: #111827; margin-top: 2px; }

    /* Custom File Input */
    .file-upload-wrapper {
        position: relative;
        display: flex; align-items: center; justify-content: center;
        padding: 16px; border: 2px dashed #d1d5db; border-radius: 8px;
        background: #f9fafb; cursor: pointer; transition: all 0.2s;
    }
    .file-upload-wrapper:hover { border-color: #3b82f6; background: #eff6ff; }
    .file-upload-input {
        position: absolute; inset: 0; width: 100%; height: 100%;
        opacity: 0; cursor: pointer;
    }
    .file-upload-content {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
        color: #6b7280; font-size: 13px; font-weight: 500;
    }
    .file-upload-content svg { width: 28px; height: 28px; color: #9ca3af; transition: color 0.2s; }
    .file-upload-wrapper:hover .file-upload-content svg { color: #3b82f6; }
    .file-name-display {
        font-size: 13px; color: #3b82f6; font-weight: 600; text-align: center;
        max-width: 90%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }


    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45); backdrop-filter: blur(2px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.2s;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal-box {
        background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        width: 100%; max-width: 500px;
        transform: scale(0.92) translateY(20px);
        transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), opacity 0.2s; opacity: 0;
    }
    .modal-overlay.open .modal-box { transform: scale(1) translateY(0); opacity: 1; }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px 14px; border-bottom: 1px solid #f3f4f6;
    }
    .modal-header-left { display: flex; align-items: center; gap: 10px; }
    .modal-icon { width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; display: flex; align-items: center; justify-content: center; }
    .modal-icon svg { width: 18px; height: 18px; color: #3b82f6; }
    .modal-title { font-size: 15px; font-weight: 700; color: #111827; }
    .modal-subtitle { font-size: 11px; color: #9ca3af; margin-top: 1px; }
    .modal-close {
        width: 30px; height: 30px; border-radius: 8px; background: #f3f4f6; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center; color: #6b7280; transition: background 0.2s;
    }
    .modal-close:hover { background: #e5e7eb; color: #111827; }
    .modal-close svg { width: 16px; height: 16px; }
    .modal-body { padding: 22px; max-height: 70vh; overflow-y: auto; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-group { margin-bottom: 16px; }
    .form-group.full { grid-column: span 2; }
    .form-label { display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-label span { color: #ef4444; }
    .form-input, .form-select {
        width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: 14px; color: #111827; background: #fff; outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
    .form-error-msg { font-size: 12px; color: #ef4444; margin-top: 4px; }
    .modal-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 8px;
        padding: 14px 22px 18px; border-top: 1px solid #f3f4f6;
    }
    .btn-save {
        display: inline-flex; align-items: center; gap: 6px; background: #3b82f6; color: #fff;
        padding: 9px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s;
    }
    .btn-save:hover { background: #2563eb; }
    .btn-save svg { width: 14px; height: 14px; }
    .btn-cancel-modal {
        display: inline-flex; align-items: center; gap: 6px; background: #f9fafb; color: #6b7280;
        padding: 9px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid #e5e7eb; cursor: pointer; transition: background 0.2s;
    }
    .btn-cancel-modal:hover { background: #f3f4f6; color: #374151; }

    /* Print styles */
    @media print {
        body * { visibility: hidden !important; }
        .print-header, .print-header *,
        .card, .card *,
        .data-table, .data-table * { visibility: visible !important; }
        .print-header {
            display: block !important; position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important;
        }
        .card {
            position: absolute !important; top: 80px !important; left: 0 !important; width: 100% !important;
            box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important;
        }
        .no-print, th:last-child, td:last-child, .pagination-area { display: none !important; }
        .data-table th, .data-table td { border: 1px solid #e5e7eb !important; }
    }
    .print-header { display: none; text-align: center; margin-bottom: 24px; }
</style>

<div class="print-header hidden">
    <h1 style="font-size: 20px; font-weight: bold;">Laporan Stok Barang</h1>
    <p style="font-size: 12px; color: #666;">Dicetak pada: <span id="produk-print-timestamp"></span></p>
</div>

<div class="page-header no-print">
    <div class="page-header-top">
        <div>
            <h2>Daftar Barang</h2>
            <p>Pantau dan kelola stok persediaan Anda</p>
        </div>
        <div class="header-actions">
            <button onclick="printBarang()" class="btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Barang
            </button>
            <button onclick="openModal('tambah')" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Barang
            </button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert-success no-print">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Lokasi</th>
                <th class="no-print">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produks as $p)
            <tr>
                <td>
                    @if($p->gambar)
                        <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_produk }}" class="prod-img">
                    @else
                        <div class="prod-img-placeholder">No Img</div>
                    @endif
                </td>
                <td><span class="sku-text">{{ $p->sku }}</span></td>
                <td><span class="prod-name">{{ $p->nama_produk }}</span></td>
                <td>
                    @php
                        $katName = $p->kategori->nama_kategori ?? '-';
                        $colors = ['bg-amber-100 text-amber-700', 'bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700', 'bg-purple-100 text-purple-700', 'bg-pink-100 text-pink-700', 'bg-indigo-100 text-indigo-700', 'bg-rose-100 text-rose-700', 'bg-cyan-100 text-cyan-700'];
                        $colorClass = $colors[abs(crc32($katName)) % count($colors)];
                    @endphp
                    <span class="badge-kategori {{ $colorClass }}">{{ $katName }}</span>
                </td>
                <td><span class="price-text">Rp {{ number_format($p->harga, 0, ',', '.') }}</span></td>
                <td>
                    <span class="badge-stok {{ $p->stok <= 30 ? 'low' : 'safe' }}">{{ $p->stok }}</span>
                </td>
                <td><span class="lokasi-text">{{ $p->lokasi }}</span></td>
                <td class="no-print">
                    <div class="action-group">
                        <button class="btn-edit" onclick="openModal('edit', {{ $p->id }}, '{{ addslashes($p->nama_produk) }}', '{{ $p->sku }}', '{{ $p->kategori_id }}', '{{ $p->harga }}', '{{ $p->stok }}', '{{ addslashes($p->lokasi) }}')">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <form action="{{ route('admin.produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $p->nama_produk }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #6b7280;">
                    Belum ada barang yang ditambahkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($produks->hasPages() || $produks->count() > 0)
    <div class="pagination-area no-print">
        <div class="pagination-info">
            Menampilkan {{ $produks->firstItem() ?? 0 }} sampai {{ $produks->lastItem() ?? 0 }} dari {{ $produks->total() }} produk
        </div>
        <div class="pagination-links">
            {{ $produks->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>

{{-- Summary Cards --}}
<div class="summary-grid no-print">
    <div class="summary-card sku">
        <div class="summary-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <div class="summary-label">Total SKU</div>
            <div class="summary-value">{{ $totalSku }} Items</div>
        </div>
    </div>
    
    <div class="summary-card aman">
        <div class="summary-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div>
            <div class="summary-label">Stok Aman</div>
            <div class="summary-value">{{ $stokAman }} Items</div>
        </div>
    </div>
    
    <div class="summary-card menipis">
        <div class="summary-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <div class="summary-label">Stok Menipis</div>
            <div class="summary-value">{{ $stokMenipis }} Items</div>
        </div>
    </div>
</div>

{{-- ===== MODAL POPUP ===== --}}
<div class="modal-overlay" id="modalOverlay" onclick="handleOverlayClick(event)">
    <div class="modal-box" id="modalBox">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <div class="modal-title" id="modalTitle">Tambah Barang</div>
                    <div class="modal-subtitle" id="modalSubtitle">Tambahkan barang baru ke inventaris</div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="modalForm" method="POST" enctype="multipart/form-data">
            @csrf
            <span id="methodField"></span>

            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label" for="modal_nama_produk">Nama Barang <span>*</span></label>
                        <input type="text" id="modal_nama_produk" name="nama_produk" class="form-input" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="modal_sku">SKU <span>*</span></label>
                        <input type="text" id="modal_sku" name="sku" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="modal_kategori_id">Kategori <span>*</span></label>
                        <select id="modal_kategori_id" name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="modal_harga">Harga (Rp) <span>*</span></label>
                        <input type="number" id="modal_harga" name="harga" class="form-input" min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="modal_stok">Stok <span>*</span></label>
                        <input type="number" id="modal_stok" name="stok" class="form-input" min="0" required>
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="modal_lokasi">Lokasi Penyimpanan <span>*</span></label>
                        <input type="text" id="modal_lokasi" name="lokasi" class="form-input" required>
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="modal_gambar">Gambar Barang</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="modal_gambar" name="gambar" class="file-upload-input" accept="image/*" onchange="document.getElementById('file_name').textContent = this.files[0] ? this.files[0].name : 'Pilih file gambar (.jpg, .png)'">
                            <div class="file-upload-content">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Klik untuk mencari file</span>
                                <div id="file_name" class="file-name-display">Pilih file gambar (.jpg, .png)</div>
                            </div>
                        </div>
                        <p style="font-size: 11px; color: #9ca3af; margin-top: 6px;">Biarkan kosong jika tidak ingin mengubah/menambahkan gambar.</p>
                    </div>
                </div>

                @if($errors->any())
                <div style="background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; font-size: 12px; margin-top: 10px;">
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save" id="modalSaveBtn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="modalSaveText">Simpan Barang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const ROUTES = {
        store:  '{{ route('admin.produk.store') }}',
        update: (id) => `/admin/barang/${id}`, // Since route resource is mapped to /admin/barang
    };

    function openModal(mode, id = null, nama = '', sku = '', kat_id = '', harga = '', stok = '', lokasi = '') {
        const overlay   = document.getElementById('modalOverlay');
        const form      = document.getElementById('modalForm');
        const title     = document.getElementById('modalTitle');
        const subtitle  = document.getElementById('modalSubtitle');
        const saveText  = document.getElementById('modalSaveText');
        const methodDiv = document.getElementById('methodField');

        // Fields
        const fNama   = document.getElementById('modal_nama_produk');
        const fSku    = document.getElementById('modal_sku');
        const fKat    = document.getElementById('modal_kategori_id');
        const fHarga  = document.getElementById('modal_harga');
        const fStok   = document.getElementById('modal_stok');
        const fLokasi = document.getElementById('modal_lokasi');

        if (mode === 'tambah') {
            title.textContent    = 'Tambah Barang';
            subtitle.textContent = 'Tambahkan barang baru ke inventaris';
            saveText.textContent = 'Tambah Barang';
            form.action          = ROUTES.store;
            methodDiv.innerHTML  = '';
            
        // Reset fields
        fNama.value = ''; fSku.value = ''; fKat.value = '';
        fHarga.value = ''; fStok.value = ''; fLokasi.value = '';
        document.getElementById('modal_gambar').value = '';
        document.getElementById('file_name').textContent = 'Pilih file gambar (.jpg, .png)';
        } else {
            title.textContent    = 'Edit Barang';
            subtitle.textContent = 'Perbarui informasi barang yang ada';
            saveText.textContent = 'Simpan Perubahan';
            form.action          = ROUTES.update(id);
            methodDiv.innerHTML  = '<input type="hidden" name="_method" value="PUT">';
            
        // Fill fields
        fNama.value = nama; fSku.value = sku; fKat.value = kat_id;
        fHarga.value = harga; fStok.value = stok; fLokasi.value = lokasi;
        document.getElementById('modal_gambar').value = '';
        document.getElementById('file_name').textContent = 'Biarkan kosong untuk mempertahankan gambar lama';
        }

        overlay.classList.add('open');
        setTimeout(() => fNama.focus(), 250);
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('open');
    }

    function handleOverlayClick(e) {
        if (e.target === document.getElementById('modalOverlay')) closeModal();
    }

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    @if($errors->any())
    document.addEventListener('DOMContentLoaded', () => openModal('tambah'));
    @endif

    function printBarang() {
        const now = new Date();
        const pad = n => String(n).padStart(2, '0');
        const tgl = pad(now.getDate()) + '/' + pad(now.getMonth() + 1) + '/' + now.getFullYear();
        const jam = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        const el = document.getElementById('produk-print-timestamp');
        if (el) el.textContent = tgl + ' ' + jam;
        window.print();
    }
</script>
@endsection
