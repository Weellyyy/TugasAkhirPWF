@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<style>
    /* Top Filter Card */
    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px 24px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f3f4f6;
    }
    
    .filter-left { display: flex; align-items: flex-end; gap: 12px; }
    
    .filter-group { display: flex; flex-direction: column; gap: 6px; }
    .filter-label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-select-wrapper {
        position: relative;
        background: #fff; border: 1px solid #d1d5db; border-radius: 8px;
        padding: 10px 36px 10px 16px; min-width: 200px;
    }
    .filter-select {
        border: none; background: transparent; font-size: 13px; font-weight: 500; color: #111827;
        padding: 0; outline: none; cursor: pointer; width: 100%;
        appearance: none; -webkit-appearance: none;
    }
    .filter-select-wrapper::after {
        content: ""; position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-size: contain; background-repeat: no-repeat;
    }
    
    .btn-filter {
        background: #2563eb; color: #fff; border: none; border-radius: 8px;
        padding: 0 24px; height: 40px; font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; justify-content: center; cursor: pointer;
        transition: background 0.2s; box-shadow: 0 2px 4px rgba(37,99,235,0.2);
    }
    .btn-filter:hover { background: #1d4ed8; }
    
    .filter-right { display: flex; align-items: center; gap: 12px; }
    
    .btn-outline {
        background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 8px;
        padding: 0 16px; height: 40px; font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s; text-decoration: none;
    }
    .btn-outline:hover { background: #f9fafb; border-color: #9ca3af; }
    .btn-outline svg { width: 16px; height: 16px; color: #6b7280; }

    /* Table Card */
    .table-card {
        background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #f3f4f6;
        padding-top: 24px; overflow: hidden; margin-bottom: 24px;
    }
    
    .table-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0 24px 20px;
    }
    .table-title { font-size: 16px; font-weight: 600; color: #111827; }
    .table-info { font-size: 12px; color: #6b7280; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f3f4f6; }
    .data-table thead th {
        padding: 14px 24px; font-size: 10px; font-weight: 700; color: #4b5563;
        text-transform: uppercase; letter-spacing: 0.8px; text-align: left;
    }
    .data-table tbody tr { border-bottom: 1px solid #f3f4f6; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table tbody tr:hover { background: #f9fafb; }
    .data-table td { padding: 18px 24px; vertical-align: top; }
    
    /* Table Data Formatting */
    .td-tanggal { display: flex; flex-direction: column; gap: 4px; }
    .date-main { font-size: 13px; font-weight: 500; color: #374151; }
    .date-sub { font-size: 11px; color: #9ca3af; }
    
    .kasir-box { display: flex; align-items: center; gap: 12px; }
    .kasir-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; flex-shrink: 0;
    }
    .avatar-blue { background: #e0e7ff; color: #3730a3; }
    .avatar-green { background: #d1fae5; color: #065f46; }
    .avatar-orange { background: #ffedd5; color: #9a3412; }
    .kasir-name { font-size: 13px; font-weight: 500; color: #374151; }
    
    .item-box { display: flex; flex-direction: column; gap: 4px; }
    .item-main { font-size: 13px; font-weight: 500; color: #374151; }
    .item-sub { font-size: 11px; color: #9ca3af; }
    
    .badge-metode {
        display: inline-block; padding: 4px 10px; border-radius: 6px; background: #f3f4f6; color: #4b5563;
        font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .badge-metode.qris { background: #e0f2fe; color: #0369a1; }
    
    .td-total { font-size: 14px; font-weight: 700; color: #111827; }
    
    .link-action { color: #2563eb; font-weight: 600; text-decoration: none; font-size: 13px; }
    .link-action:hover { text-decoration: underline; }

    .pagination-wrapper { padding: 16px 24px; border-top: 1px solid #f3f4f6; background: #fff; }

    /* Bottom Summary Cards */
    .summary-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 800px;
    }
    .summary-card {
        background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f3f4f6; display: flex; flex-direction: column; justify-content: center;
        position: relative; overflow: hidden;
    }
    .card-border-green { border-left: 4px solid #10b981; }
    .card-border-blue { border-left: 4px solid #3b82f6; }
    
    .summary-icon-wrapper {
        width: 48px; height: 48px; border-radius: 8px; margin-bottom: 16px;
        display: flex; align-items: center; justify-content: center;
    }
    .bg-light-green { background: #ecfdf5; color: #10b981; }
    .bg-light-blue { background: #eff6ff; color: #3b82f6; }
    
    .summary-label { font-size: 12px; color: #6b7280; font-weight: 500; margin-bottom: 6px; }
    .summary-value { font-size: 24px; font-weight: 700; color: #111827; }

    @media print {
        body * { visibility: hidden !important; }
        .print-area, .print-area * { visibility: visible !important; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
        .table-card { box-shadow: none; border: none; }
        th, td { border: 1px solid #e5e7eb; }
    }
</style>

<div class="filter-card no-print">
    <form action="{{ route('admin.reports.index') }}" method="GET" class="filter-left">
        <div class="filter-group">
            <span class="filter-label">Periode Laporan</span>
            <div class="filter-select-wrapper">
                <select name="filter_type" class="filter-select">
                    <option value="semua" {{ $filterType == 'semua' ? 'selected' : '' }}>Semua Penjualan</option>
                    <option value="hari" {{ $filterType == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu" {{ $filterType == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan" {{ $filterType == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun" {{ $filterType == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn-filter">
            Filter
        </button>
    </form>
    
    <div class="filter-right">
        <button type="button" class="btn-outline" onclick="window.print()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak
        </button>
        <a href="{{ route('admin.reports.index', ['filter_type' => $filterType, 'export' => 'csv']) }}" class="btn-outline">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </a>
    </div>
</div>

<div class="print-area">
    <div class="table-card">
        <div class="table-header">
            <div class="table-title">Detail Transaksi</div>
            <div class="table-info no-print">
                Showing {{ $transaksis->firstItem() ?? 0 }} to {{ $transaksis->lastItem() ?? 0 }} of {{ $transaksis->total() }} Transactions
            </div>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>TANGGAL</th>
                    <th>KASIR</th>
                    <th>ITEM PESANAN</th>
                    <th>METODE</th>
                    <th>TOTAL</th>
                    <th class="no-print">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                @php
                    $kasirName = $t->kasir->nama ?? 'Sistem';
                    // Generate Initials
                    $words = explode(' ', $kasirName);
                    $initials = strtoupper(substr($words[0], 0, 1));
                    if(isset($words[1])) {
                        $initials .= strtoupper(substr($words[1], 0, 1));
                    } else {
                        $initials .= strtoupper(substr($words[0], 1, 1));
                    }
                    
                    // Generate color variation based on ID
                    $colors = ['avatar-blue', 'avatar-green', 'avatar-orange'];
                    $kasirId = $t->kasir->id ?? 0;
                    $avatarColor = $colors[$kasirId % count($colors)];

                    // Format items
                    $itemNames = [];
                    $itemPrices = [];
                    foreach($t->detail as $d) {
                        $prod = $d->produk->nama_produk ?? 'Produk Dihapus';
                        $itemNames[] = "{$prod} (x{$d->jumlah})";
                        $itemPrices[] = "Rp. " . number_format($d->subtotal, 0, ',', '.');
                    }
                @endphp
                <tr>
                    <td>
                        <div class="td-tanggal">
                            <span class="date-main">{{ $t->created_at->format('d M Y') }}</span>
                            <span class="date-sub">{{ $t->created_at->format('H:i') }} WIB</span>
                        </div>
                    </td>
                    <td>
                        <div class="kasir-box">
                            <div class="kasir-avatar {{ $avatarColor }}">{{ $initials }}</div>
                            <span class="kasir-name">{{ $kasirName }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="item-box">
                            <span class="item-main">{{ implode(', ', $itemNames) }}</span>
                            <span class="item-sub">{{ implode(' + ', $itemPrices) }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-metode {{ strtolower($t->metode_pembayaran) }}">
                            {{ $t->metode_pembayaran }}
                        </span>
                    </td>
                    <td class="td-total">Rp. {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                    <td class="no-print">
                        <a href="#" class="link-action" onclick="alert('Fitur cetak struk belum diimplementasikan.')">Struk</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #6b7280;">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($transaksis->hasPages() || $transaksis->count() > 0)
        <div class="pagination-wrapper no-print">
            {{ $transaksis->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
</div>

<div class="summary-grid no-print">
    <div class="summary-card card-border-green">
        <div class="summary-icon-wrapper bg-light-green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div class="summary-label">Total Pendapatan</div>
        <div class="summary-value">Rp. {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>
    
    <div class="summary-card card-border-blue">
        <div class="summary-icon-wrapper bg-light-blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <div class="summary-label">Jumlah Transaksi</div>
        <div class="summary-value">{{ number_format($jumlahTransaksi, 0, ',', '.') }} Transaksi</div>
    </div>
</div>

@endsection
