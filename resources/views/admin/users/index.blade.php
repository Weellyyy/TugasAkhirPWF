@extends('layouts.admin')

@section('title', 'Manajemen Kasir')

@section('content')
<style>
    .page-header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-header-left h2 { font-size: 22px; font-weight: 700; color: #111827; }
    .page-header-left p  { font-size: 13px; color: #6b7280; margin-top: 4px; }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        background: #2563eb; color: #fff;
        padding: 10px 20px; border-radius: 8px;
        font-size: 14px; font-weight: 600;
        border: none; cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        box-shadow: 0 2px 8px rgba(37,99,235,0.3);
        text-decoration: none;
    }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-primary svg { width: 18px; height: 18px; }

    /* Alert */
    .alert-success {
        display: flex; align-items: center; gap: 10px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        color: #15803d; border-radius: 10px;
        padding: 12px 16px; margin-bottom: 20px;
        font-size: 13px; font-weight: 500;
    }
    .alert-success svg { width: 16px; height: 16px; flex-shrink: 0; }

    /* Card & Table Header Tools */
    .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 24px; }
    
    .table-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid #f3f4f6;
    }
    .search-box {
        position: relative; width: 300px;
    }
    .search-box input {
        width: 100%; padding: 10px 14px 10px 36px; border-radius: 8px;
        border: 1px solid #e5e7eb; background: #f9fafb; font-size: 13px;
        outline: none; transition: border-color 0.2s;
    }
    .search-box input:focus { border-color: #3b82f6; background: #fff; }
    .search-box svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af; }
    
    .toolbar-actions { display: flex; align-items: center; gap: 8px; }
    .btn-icon {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        border-radius: 8px; border: 1px solid #e5e7eb; background: #fff;
        color: #4b5563; cursor: pointer; transition: all 0.2s;
    }
    .btn-icon:hover { background: #f3f4f6; color: #111827; border-color: #d1d5db; }
    .btn-icon svg { width: 16px; height: 16px; }

    /* Table */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f9fafb; }
    .data-table thead th {
        padding: 14px 20px;
        font-size: 11px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.8px;
        text-align: left; border-bottom: 1px solid #f3f4f6;
    }
    .data-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background 0.15s; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }
    .data-table td { padding: 16px 20px; font-size: 13px; color: #374151; vertical-align: middle; }
    
    .text-dark { font-weight: 700; color: #111827; }
    .text-gray { color: #6b7280; }

    .badge-role {
        display: inline-block; padding: 4px 12px; border-radius: 20px;
        font-size: 11px; font-weight: 700; text-transform: capitalize;
    }
    .badge-role.admin { background: #6ee7b7; color: #064e3b; } /* Matches Kategori/Stok Makanan */
    .badge-role.kasir { background: #bfdbfe; color: #1e3a8a; } /* Matches Kategori/Stok Minuman */

    .status-dot {
        display: inline-flex; align-items: center; gap: 6px; font-weight: 600; font-size: 12px;
    }
    .status-dot::before {
        content: ""; display: block; width: 6px; height: 6px; border-radius: 50%;
    }
    .status-dot.aktif { color: #10b981; }
    .status-dot.aktif::before { background: #10b981; }
    .status-dot.nonaktif { color: #ef4444; }
    .status-dot.nonaktif::before { background: #ef4444; }

    .action-group { display: flex; align-items: center; gap: 8px; }
    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        background: #eff6ff; color: #2563eb; padding: 6px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s;
    }
    .btn-edit:hover { background: #dbeafe; }
    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        background: #fef2f2; color: #dc2626; padding: 6px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s;
    }
    .btn-delete:hover { background: #fee2e2; }

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
        background: #f9fafb; border-radius: 12px; padding: 20px;
        display: flex; align-items: center; gap: 16px;
        border: 1px solid #e5e7eb;
    }
    .summary-icon {
        width: 48px; height: 48px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .summary-card:nth-child(1) .summary-icon { background: #064e3b; color: #6ee7b7; }
    .summary-card:nth-child(2) .summary-icon { background: #1e1b4b; color: #818cf8; }
    .summary-card:nth-child(3) .summary-icon { background: #dbeafe; color: #2563eb; }
    .summary-icon svg { width: 20px; height: 20px; }

    .summary-label { font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .summary-value { font-size: 18px; font-weight: 800; color: #111827; margin-top: 2px; }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45); backdrop-filter: blur(2px);
        display: flex; align-items: center; justify-content: center; z-index: 9999;
        opacity: 0; pointer-events: none; transition: opacity 0.2s;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal-box {
        background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        width: 100%; max-width: 500px; transform: scale(0.92) translateY(20px);
        transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), opacity 0.2s; opacity: 0;
    }
    .modal-overlay.open .modal-box { transform: scale(1) translateY(0); opacity: 1; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px 14px; border-bottom: 1px solid #f3f4f6; }
    .modal-header-left { display: flex; align-items: center; gap: 10px; }
    .modal-icon { width: 36px; height: 36px; border-radius: 10px; background: #eff6ff; display: flex; align-items: center; justify-content: center; }
    .modal-icon svg { width: 18px; height: 18px; color: #3b82f6; }
    .modal-title { font-size: 15px; font-weight: 700; color: #111827; }
    .modal-subtitle { font-size: 11px; color: #9ca3af; margin-top: 1px; }
    .modal-close { width: 30px; height: 30px; border-radius: 8px; background: #f3f4f6; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #6b7280; transition: background 0.2s; }
    .modal-close:hover { background: #e5e7eb; color: #111827; }
    .modal-close svg { width: 16px; height: 16px; }
    .modal-body { padding: 22px; max-height: 70vh; overflow-y: auto; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 20px; margin-bottom: 8px; }
    .form-group { margin-bottom: 12px; }
    .form-group.full { grid-column: span 2; }
    .form-label { display: block; font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-label span { color: #ef4444; }
    .form-input, .form-select { width: 100%; padding: 10px 14px; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 13px; color: #111827; background: #fff; outline: none; transition: border-color 0.2s; }
    .form-input:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
    .modal-footer { display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding: 16px 22px 18px; border-top: 1px solid #f3f4f6; background: #f9fafb; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; }
    .btn-save { display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #3b82f6; color: #fff; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s; box-shadow: 0 2px 4px rgba(59,130,246,0.2); }
    .btn-save:hover { background: #2563eb; }
    .btn-save svg { width: 16px; height: 16px; }
    .btn-cancel-modal { display: inline-flex; align-items: center; justify-content: center; gap: 6px; background: #fff; color: #4b5563; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; border: 1px solid #d1d5db; cursor: pointer; transition: all 0.2s; }
    .btn-cancel-modal:hover { background: #f3f4f6; color: #1f2937; }
</style>

<div class="page-header">
    <div class="page-header-left">
        <h2>Daftar Kasir / Pengguna</h2>
        <p>Kelola akses akun, peranan pengguna, dan status operasional staf kasir Anda.</p>
    </div>
    <button onclick="openModal('tambah')" class="btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Tambah Kasir / Pengguna
    </button>
</div>

@if(session('success'))
<div class="alert-success">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if($errors->has('error'))
<div class="alert-success" style="background:#fef2f2;border-color:#fca5a5;color:#b91c1c;">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ $errors->first('error') }}
</div>
@endif

<div class="card">
    <div class="table-toolbar">
        <div class="search-box">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" placeholder="Cari nama atau email...">
        </div>
        <div class="toolbar-actions">
            <button class="btn-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </button>
            <button class="btn-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </button>
        </div>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAMA</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>STATUS</th>
                <th>AKSI</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td class="text-gray">#{{ $u->id }}</td>
                <td class="text-dark">{{ $u->nama }}</td>
                <td class="text-gray">{{ $u->username }}</td>
                <td class="text-gray">{{ $u->email }}</td>
                <td>
                    <span class="badge-role {{ strtolower($u->role) }}">{{ ucfirst($u->role) }}</span>
                </td>
                <td>
                    <span class="status-dot {{ strtolower($u->status) }}">{{ $u->status }}</span>
                </td>
                <td>
                    <div class="action-group">
                        <button class="btn-edit" onclick="openModal('edit', {{ $u->id }}, '{{ addslashes($u->nama) }}', '{{ $u->username }}', '{{ $u->email }}', '{{ $u->role }}', '{{ $u->status }}')">
                            Edit
                        </button>
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $u->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                        @else
                        <span style="font-size:12px;color:#9ca3af;font-style:italic;">Sendiri</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($users->hasPages() || $users->count() > 0)
    <div class="pagination-area">
        <div class="pagination-info">
            Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </div>
        <div class="pagination-links">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
    @endif
</div>



{{-- ===== MODAL POPUP ===== --}}
<div class="modal-overlay" id="modalOverlay" onclick="handleOverlayClick(event)">
    <div class="modal-box" id="modalBox">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <div>
                    <div class="modal-title" id="modalTitle">Tambah Pengguna</div>
                    <div class="modal-subtitle" id="modalSubtitle">Buat akun akses untuk staf baru</div>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="modalForm" method="POST">
            @csrf
            <span id="methodField"></span>

            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label" for="modal_nama">Nama Lengkap <span>*</span></label>
                        <input type="text" id="modal_nama" name="nama" class="form-input" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="modal_username">Username <span>*</span></label>
                        <input type="text" id="modal_username" name="username" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="modal_email">Email <span>*</span></label>
                        <input type="email" id="modal_email" name="email" class="form-input" required>
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="modal_password">Password</label>
                        <input type="password" id="modal_password" name="password" class="form-input">
                        <p class="form-hint" id="passwordHint" style="font-size: 11px; color: #9ca3af; margin-top: 4px;">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="modal_role">Role / Peran <span>*</span></label>
                        <select id="modal_role" name="role" class="form-select" required>
                            <option value="kasir">Kasir</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="modal_status">Status <span>*</span></label>
                        <select id="modal_status" name="status" class="form-select" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                @if($errors->any() && !$errors->has('error'))
                <div style="background: #fef2f2; color: #b91c1c; padding: 12px; border-radius: 8px; font-size: 12px; margin-top: 10px;">
                    <ul style="list-style-type: disc; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            @if($error !== $errors->first('error'))
                            <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save" id="modalSaveBtn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span id="modalSaveText">Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const ROUTES = {
        store:  '{{ route('admin.users.store') }}',
        update: (id) => `/admin/users/${id}`,
    };

    function openModal(mode, id = null, nama = '', username = '', email = '', role = 'kasir', status = 'Aktif') {
        const overlay   = document.getElementById('modalOverlay');
        const form      = document.getElementById('modalForm');
        const title     = document.getElementById('modalTitle');
        const subtitle  = document.getElementById('modalSubtitle');
        const saveText  = document.getElementById('modalSaveText');
        const methodDiv = document.getElementById('methodField');
        const pwHint    = document.getElementById('passwordHint');

        // Fields
        const fNama     = document.getElementById('modal_nama');
        const fUser     = document.getElementById('modal_username');
        const fEmail    = document.getElementById('modal_email');
        const fPass     = document.getElementById('modal_password');
        const fRole     = document.getElementById('modal_role');
        const fStatus   = document.getElementById('modal_status');

        if (mode === 'tambah') {
            title.textContent    = 'Tambah Pengguna';
            subtitle.textContent = 'Buat akun akses untuk staf baru';
            saveText.textContent = 'Tambah Pengguna';
            form.action          = ROUTES.store;
            methodDiv.innerHTML  = '';
            pwHint.textContent   = 'Wajib diisi untuk pengguna baru (Min. 6 karakter).';
            fPass.required       = true;
            
            // Reset fields
            fNama.value = ''; fUser.value = ''; fEmail.value = ''; fPass.value = '';
            fRole.value = 'kasir'; fStatus.value = 'Aktif';
        } else {
            title.textContent    = 'Edit Pengguna';
            subtitle.textContent = 'Perbarui informasi dan akses akun';
            saveText.textContent = 'Simpan Perubahan';
            form.action          = ROUTES.update(id);
            methodDiv.innerHTML  = '<input type="hidden" name="_method" value="PUT">';
            pwHint.textContent   = 'Kosongkan jika tidak ingin mengubah password.';
            fPass.required       = false;
            
            // Fill fields
            fNama.value = nama; fUser.value = username; fEmail.value = email; fPass.value = '';
            fRole.value = role; fStatus.value = status;
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

    @if($errors->any() && !$errors->has('error'))
    document.addEventListener('DOMContentLoaded', () => openModal('tambah'));
    @endif
</script>
@endsection
