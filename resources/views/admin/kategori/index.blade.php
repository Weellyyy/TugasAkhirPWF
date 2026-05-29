@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
<style>
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px;
    }
    .page-header-left h2 { font-size: 16px; font-weight: 700; color: #111827; }
    .page-header-left p  { font-size: 12px; color: #6b7280; margin-top: 2px; }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 6px;
        background: #3b82f6; color: #fff;
        padding: 9px 18px; border-radius: 8px;
        font-size: 13px; font-weight: 600;
        border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(59,130,246,0.3);
        text-decoration: none;
    }
    .btn-primary:hover { background: #2563eb; transform: translateY(-1px); }
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
    .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; overflow: hidden; }
    .card-header {
        padding: 14px 20px; border-bottom: 1px solid #f3f4f6;
        display: flex; align-items: center; gap: 8px; background: #fafafa;
    }
    .card-header svg { width: 16px; height: 16px; color: #6b7280; }
    .card-header span { font-size: 13px; font-weight: 600; color: #374151; }
    .card-header .count-badge {
        margin-left: auto;
        background: #e0f2fe; color: #0369a1;
        font-size: 11px; font-weight: 700;
        padding: 2px 8px; border-radius: 20px;
    }

    /* Table */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f9fafb; }
    .data-table thead th {
        padding: 10px 20px;
        font-size: 10px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.8px;
        text-align: left; border-bottom: 1px solid #f3f4f6;
    }
    .data-table thead th:last-child { text-align: right; }
    .data-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background 0.15s; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }
    .data-table td { padding: 14px 20px; font-size: 13px; color: #374151; vertical-align: middle; }
    .data-table td:last-child { text-align: right; }

    .id-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; background: #f3f4f6; color: #6b7280;
        border-radius: 6px; font-size: 11px; font-weight: 700;
    }
    .kat-cell { display: flex; align-items: center; gap: 10px; }
    .kat-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .kat-icon.makanan { background: #fef9c3; } .kat-icon.makanan svg { color: #ca8a04; }
    .kat-icon.minuman { background: #e0f2fe; } .kat-icon.minuman svg { color: #0284c7; }
    .kat-icon.default { background: #f3f4f6; } .kat-icon.default svg { color: #9ca3af; }
    .kat-icon svg { width: 18px; height: 18px; }
    .kat-name { font-weight: 600; color: #111827; font-size: 13px; }

    .action-group { display: flex; align-items: center; justify-content: flex-end; gap: 8px; }
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

    .empty-state { padding: 48px; text-align: center; color: #9ca3af; }
    .empty-state svg { width: 40px; height: 40px; margin: 0 auto 10px; display: block; color: #d1d5db; }
    .empty-state p { font-size: 14px; }

    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(2px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
        opacity: 0; pointer-events: none;
        transition: opacity 0.2s;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }

    .modal-box {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        width: 100%; max-width: 420px;
        transform: scale(0.92) translateY(20px);
        transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), opacity 0.2s;
        opacity: 0;
    }
    .modal-overlay.open .modal-box { transform: scale(1) translateY(0); opacity: 1; }

    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid #f3f4f6;
    }
    .modal-header-left { display: flex; align-items: center; gap: 10px; }
    .modal-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #eff6ff;
        display: flex; align-items: center; justify-content: center;
    }
    .modal-icon svg { width: 18px; height: 18px; color: #3b82f6; }
    .modal-title { font-size: 15px; font-weight: 700; color: #111827; }
    .modal-subtitle { font-size: 11px; color: #9ca3af; margin-top: 1px; }
    .modal-close {
        width: 30px; height: 30px; border-radius: 8px;
        background: #f3f4f6; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #6b7280; transition: background 0.2s;
    }
    .modal-close:hover { background: #e5e7eb; color: #111827; }
    .modal-close svg { width: 16px; height: 16px; }

    .modal-body { padding: 22px; }

    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block; font-size: 11px; font-weight: 700; color: #374151;
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .form-label span { color: #ef4444; }
    .form-input {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-size: 14px; color: #111827; background: #fff;
        outline: none; transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
    .form-hint { font-size: 11px; color: #9ca3af; margin-top: 4px; }
    .form-error-msg { font-size: 12px; color: #ef4444; margin-top: 4px; }

    .modal-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 8px;
        padding: 14px 22px 18px;
        border-top: 1px solid #f3f4f6;
    }
    .btn-save {
        display: inline-flex; align-items: center; gap: 6px;
        background: #3b82f6; color: #fff;
        padding: 9px 20px; border-radius: 8px;
        font-size: 13px; font-weight: 600; border: none; cursor: pointer;
        transition: background 0.2s; box-shadow: 0 2px 8px rgba(59,130,246,0.3);
    }
    .btn-save:hover { background: #2563eb; }
    .btn-save svg { width: 14px; height: 14px; }
    .btn-cancel-modal {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f9fafb; color: #6b7280;
        padding: 9px 16px; border-radius: 8px;
        font-size: 13px; font-weight: 600; border: 1px solid #e5e7eb; cursor: pointer;
        transition: background 0.2s;
    }
    .btn-cancel-modal:hover { background: #f3f4f6; color: #374151; }
</style>

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <h2>Manajemen Kategori</h2>
        <p>Kelola kategori produk yang tersedia</p>
    </div>
    <button class="btn-primary" onclick="openModal('tambah')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </button>
</div>

{{-- ALERT --}}
@if(session('success'))
<div class="alert-success">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- TABLE CARD --}}
<div class="card">
    <div class="card-header">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        <span>Daftar Kategori</span>
        <span class="count-badge">{{ $kategoris->count() }} kategori</span>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:60px">No</th>
                <th>Nama Kategori</th>
                <th>Jumlah Produk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategoris as $i => $k)
            <tr>
                <td><span class="id-badge">{{ $i + 1 }}</span></td>
                <td>
                    <div class="kat-cell">
                        @php
                            $katName = $k->nama_kategori ?? '-';
                            $katLower = strtolower($katName);
                            $colors = ['bg-amber-100 text-amber-700', 'bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700', 'bg-purple-100 text-purple-700', 'bg-pink-100 text-pink-700', 'bg-indigo-100 text-indigo-700', 'bg-rose-100 text-rose-700', 'bg-cyan-100 text-cyan-700'];
                            $colorClass = $colors[abs(crc32($katName)) % count($colors)];
                            
                            if (str_contains($katLower, 'makan')) {
                                $svg = '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>';
                            } elseif (str_contains($katLower, 'minum')) {
                                $svg = '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>';
                            } else {
                                $svg = '<svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>';
                            }
                        @endphp
                        <div class="w-[34px] h-[34px] rounded-lg flex items-center justify-center shrink-0 {{ $colorClass }}">
                            {!! $svg !!}
                        </div>
                        <span class="kat-name">{{ $k->nama_kategori }}</span>
                    </div>
                </td>
                <td><span style="font-size:13px;color:#6b7280;">{{ $k->produks_count }} produk</span></td>
                <td>
                    <div class="action-group">
                        <button class="btn-edit"
                            onclick="openModal('edit', {{ $k->id }}, '{{ addslashes($k->nama_kategori) }}')">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </button>
                        <form action="{{ route('admin.kategori.destroy', $k->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus kategori \'{{ $k->nama_kategori }}\'?')">
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
            <tr><td colspan="4">
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p>Belum ada kategori. Klik "Tambah Kategori" untuk memulai!</p>
                </div>
            </td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== MODAL POPUP ===== --}}
<div class="modal-overlay" id="modalOverlay" onclick="handleOverlayClick(event)">
    <div class="modal-box" id="modalBox">

        {{-- Header --}}
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <div class="modal-title" id="modalTitle">Tambah Kategori</div>
                    <div class="modal-subtitle" id="modalSubtitle">Buat kategori produk baru</div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <form id="modalForm" method="POST">
            @csrf
            <span id="methodField"></span>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="modal_nama_kategori">
                        Nama Kategori <span>*</span>
                    </label>
                    <input
                        type="text"
                        id="modal_nama_kategori"
                        name="nama_kategori"
                        class="form-input"
                        placeholder="Contoh: Makanan, Minuman, Snack..."
                        required
                        autofocus
                    >
                    @error('nama_kategori')
                    <p class="form-error-msg">{{ $message }}</p>
                    @enderror
                    <p class="form-hint">Nama ini ditampilkan sebagai filter kategori pada halaman kasir.</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save" id="modalSaveBtn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="modalSaveText">Tambah Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const ROUTES = {
        store:  '{{ route('admin.kategori.store') }}',
        update: (id) => `/admin/kategori/${id}`,
        // prefix already included above
    };

    function openModal(mode, id = null, nama = '') {
        const overlay   = document.getElementById('modalOverlay');
        const form      = document.getElementById('modalForm');
        const title     = document.getElementById('modalTitle');
        const subtitle  = document.getElementById('modalSubtitle');
        const input     = document.getElementById('modal_nama_kategori');
        const saveText  = document.getElementById('modalSaveText');
        const methodDiv = document.getElementById('methodField');

        if (mode === 'tambah') {
            title.textContent    = 'Tambah Kategori';
            subtitle.textContent = 'Buat kategori produk baru';
            saveText.textContent = 'Tambah Kategori';
            form.action          = ROUTES.store;
            input.value          = '';
            methodDiv.innerHTML  = '';
        } else {
            title.textContent    = 'Edit Kategori';
            subtitle.textContent = 'Ubah nama kategori yang dipilih';
            saveText.textContent = 'Simpan Perubahan';
            form.action          = ROUTES.update(id);
            input.value          = nama;
            methodDiv.innerHTML  = '<input type="hidden" name="_method" value="PUT">';
        }

        overlay.classList.add('open');
        setTimeout(() => input.focus(), 250);
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('open');
    }

    function handleOverlayClick(e) {
        if (e.target === document.getElementById('modalOverlay')) closeModal();
    }

    // ESC to close
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    // Auto open if there are validation errors
    @if($errors->any())
    document.addEventListener('DOMContentLoaded', () => openModal('tambah'));
    @endif
</script>
@endpush
@endsection
