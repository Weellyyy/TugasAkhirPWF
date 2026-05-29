@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    /* ===== STAT CARDS ===== */
    .stat-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px 22px;
        display: flex; align-items: center; gap: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); transform: translateY(-2px); }
    .stat-card.green { border-top: 3px solid #22c55e; }
    .stat-card.blue  { border-top: 3px solid #3b82f6; }
    .stat-card.gray  { border-top: 3px solid #d1d5db; }

    .stat-icon {
        width: 50px; height: 50px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon.green { background: #dcfce7; }
    .stat-icon.blue  { background: #dbeafe; }
    .stat-icon.gray  { background: #f3f4f6; }
    .stat-icon svg { width: 26px; height: 26px; }
    .stat-icon.green svg { color: #16a34a; }
    .stat-icon.blue  svg { color: #2563eb; }
    .stat-icon.gray  svg { color: #9ca3af; }

    .stat-info {}
    .stat-label { font-size: 10px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; }
    .stat-value { font-size: 22px; font-weight: 800; color: #111827; line-height: 1.2; }
    .stat-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }

    /* QUICK ACTION placeholder */
    .stat-card-placeholder {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        color: #d1d5db; font-size: 13px; font-style: italic;
        border-top: 3px solid #e5e7eb;
    }

    /* ===== LOW STOCK TABLE ===== */
    .low-stock-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .low-stock-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
    }
    .low-stock-title {
        display: flex; align-items: center; gap: 8px;
        font-size: 14px; font-weight: 700; color: #111827;
    }
    .low-stock-title svg { width: 18px; height: 18px; color: #f59e0b; }
    .low-stock-link {
        font-size: 12px; color: #3b82f6; font-weight: 600;
        text-decoration: none; transition: color 0.2s;
    }
    .low-stock-link:hover { color: #1d4ed8; text-decoration: underline; }

    .ls-table { width: 100%; border-collapse: collapse; }
    .ls-table thead tr { background: #f9fafb; }
    .ls-table thead th {
        padding: 10px 20px;
        font-size: 10px; font-weight: 700; color: #6b7280;
        text-transform: uppercase; letter-spacing: 0.8px;
        text-align: left; border-bottom: 1px solid #f3f4f6;
    }
    .ls-table thead th:last-child { text-align: right; }
    .ls-table tbody tr { border-bottom: 1px solid #f9fafb; transition: background 0.15s; }
    .ls-table tbody tr:last-child { border-bottom: none; }
    .ls-table tbody tr:hover { background: #f9fafb; }
    .ls-table td { padding: 14px 20px; font-size: 13px; color: #374151; vertical-align: middle; }
    .ls-table td:last-child { text-align: right; }

    .prod-cell { display: flex; align-items: center; gap: 10px; }
    .prod-icon {
        width: 34px; height: 34px;
        background: #f3f4f6; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
    }
    .prod-icon svg { width: 18px; height: 18px; color: #6b7280; }
    .prod-name { font-weight: 600; color: #111827; font-size: 13px; }

    .badge-kategori {
        display: inline-block;
        padding: 3px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 600;
        background: #e0f2fe; color: #0369a1;
    }
    .badge-kategori.makanan { background: #fef9c3; color: #92400e; }
    .badge-kategori.minuman { background: #e0f2fe; color: #0369a1; }

    .stok-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #fed7aa; color: #c2410c;
        font-size: 12px; font-weight: 700;
    }
    .stok-badge.critical { background: #fee2e2; color: #b91c1c; }

    .btn-update-stok {
        display: inline-block;
        background: #3b82f6;
        color: #fff;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: 12px; font-weight: 600;
        text-decoration: none;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-update-stok:hover { background: #2563eb; transform: scale(1.03); }

    .empty-state {
        padding: 40px; text-align: center; color: #9ca3af; font-size: 14px;
    }
    .empty-state svg { width: 40px; height: 40px; margin: 0 auto 8px; display: block; color: #d1d5db; }
</style>

{{-- STAT CARDS --}}
<div class="stat-cards">
    {{-- Total Pendapatan --}}
    <div class="stat-card green">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Total Penjualan --}}
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value">{{ $jumlahPenjualan }}</div>
            <div class="stat-sub">Transaksi</div>
        </div>
    </div>

    {{-- Quick Action Widget --}}
    <div class="stat-card-placeholder">
        Quick Action Widget
    </div>
</div>

{{-- PRODUK HAMPIR HABIS --}}
<div class="low-stock-card">
    <div class="low-stock-header">
        <div class="low-stock-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Produk Hampir Habis (Stok &le; 5)
        </div>
        <a href="{{ route('admin.produk.index') }}" class="low-stock-link">Lihat Semua Stok</a>
    </div>

    @if($produkHampirHabis->count() > 0)
    <table class="ls-table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Sisa Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produkHampirHabis as $produk)
            <tr>
                <td>
                    <div class="prod-cell">
                        <div class="prod-icon">
                            @if($produk->kategori && strtolower($produk->kategori->nama_kategori) === 'minuman')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                            </svg>
                            @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            @endif
                        </div>
                        <span class="prod-name">{{ $produk->nama_produk }}</span>
                    </div>
                </td>
                <td>
                    @php
                        $kat = strtolower($produk->kategori->nama_kategori ?? '');
                        $badgeClass = ($kat === 'minuman') ? 'minuman' : 'makanan';
                    @endphp
                    <span class="badge-kategori {{ $badgeClass }}">
                        {{ $produk->kategori->nama_kategori ?? '-' }}
                    </span>
                </td>
                <td>
                    <span class="stok-badge {{ $produk->stok <= 2 ? 'critical' : '' }}">
                        {{ $produk->stok }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.produk.edit', $produk->id) }}" class="btn-update-stok">Update Stok</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Semua stok produk dalam keadaan aman 👍
    </div>
    @endif
</div>
@endsection

@section('fab')
<button class="fab" title="Buka POS" onclick="window.location='{{ route('pos') }}'">+</button>
@endsection
