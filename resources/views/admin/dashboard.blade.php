@extends('layouts.admin')
@section('title', 'Dashboard Utama')
@section('content')
<style>
    /* Reset & Base */
    .dashboard-container {
        font-family: 'Inter', sans-serif;
        color: #111827;
        padding-bottom: 40px;
    }
    
    /* Header Section */
    .dash-header {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 24px;
    }
    .dh-left .dh-sub {
        font-size: 11px; font-weight: 700; color: #3b82f6; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 4px;
    }
    .dh-left .dh-title {
        font-size: 26px; font-weight: 800; color: #0f172a; margin: 0; line-height: 1.2;
    }
    .dh-right { display: flex; gap: 12px; }
    .btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 8px 16px; font-size: 13px; font-weight: 600; color: #475569;
        cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-outline svg { width: 16px; height: 16px; color: #64748b; }
    
    /* Stats Row */
    .stats-row {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;
    }
    .stat-box {
        background: #fff; border-radius: 12px; padding: 20px;
        border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        position: relative;
    }
    .stat-box-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .sb-icon {
        width: 44px; height: 44px; border-radius: 10px; background: #eff6ff;
        display: flex; align-items: center; justify-content: center;
    }
    .sb-icon svg { width: 22px; height: 22px; color: #3b82f6; }
    .sb-trend {
        display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 20px;
        font-size: 11px; font-weight: 700;
    }
    .sb-trend.up { background: #dcfce7; color: #15803d; }
    .sb-trend.down { background: #fee2e2; color: #b91c1c; }
    
    .sb-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .sb-value { font-size: 22px; font-weight: 800; color: #0f172a; }
    
    /* Chart Section */
    .chart-section {
        background: #fff; border-radius: 16px; padding: 24px;
        border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        margin-bottom: 24px;
    }
    .chart-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .ch-title { font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .ch-sub { font-size: 12px; color: #64748b; }
    .ch-legend { display: flex; gap: 16px; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: #475569; }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; }
    .legend-dot.blue { background: #3b82f6; }
    .legend-dot.gray { background: #cbd5e1; }
    .chart-container { height: 280px; width: 100%; position: relative; }
    
    /* Bottom Grid */
    .bottom-grid {
        display: grid; grid-template-columns: 2fr 1fr; gap: 24px;
    }
    
    /* Panel Shared */
    .panel {
        background: #fff; border-radius: 16px; padding: 24px;
        border: 1px solid #f1f5f9; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .panel-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
    }
    .panel-title { font-size: 16px; font-weight: 700; color: #0f172a; }
    .panel-link { font-size: 12px; font-weight: 600; color: #3b82f6; text-decoration: none; }
    .panel-link:hover { text-decoration: underline; }
    
    /* Table Produk Terlaris */
    .pt-table { width: 100%; border-collapse: collapse; }
    .pt-table th {
        text-align: left; padding-bottom: 12px; font-size: 10px; font-weight: 700; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #f1f5f9;
    }
    .pt-table td { padding: 16px 0; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
    .pt-table tr:last-child td { border-bottom: none; }
    
    .pt-product { display: flex; align-items: center; gap: 12px; }
    .pt-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; background: #f1f5f9; }
    .pt-img-fallback { width: 40px; height: 40px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #94a3b8; }
    .pt-name { font-size: 13px; font-weight: 600; color: #0f172a; }
    .pt-cat { font-size: 12px; color: #64748b; }
    .pt-qty { font-size: 13px; font-weight: 600; color: #475569; }
    .pt-rev { font-size: 13px; font-weight: 700; color: #0f172a; }
    
    /* Stok Menipis */
    .sm-badge {
        background: #fee2e2; color: #b91c1c; font-size: 10px; font-weight: 800;
        padding: 4px 8px; border-radius: 6px; letter-spacing: 0.5px;
    }
    .sm-list { display: flex; flex-direction: column; gap: 12px; }
    .sm-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px; border: 1px solid #f1f5f9; border-radius: 12px;
        transition: border-color 0.2s;
    }
    .sm-item:hover { border-color: #e2e8f0; }
    .sm-left { display: flex; align-items: center; gap: 12px; }
    .sm-icon {
        width: 38px; height: 38px; border-radius: 8px; background: #f8fafc;
        display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9;
    }
    .sm-icon svg { width: 18px; height: 18px; color: #64748b; }
    .sm-info {}
    .sm-name { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
    .sm-stock { font-size: 11px; font-weight: 600; color: #64748b; }
    .sm-stock span { color: #b91c1c; }
    .btn-order {
        background: #eff6ff; color: #1d4ed8; border: none; padding: 6px 14px;
        border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer;
        transition: background 0.2s; text-decoration: none;
    }
    .btn-order:hover { background: #dbeafe; }
    
    .sm-footer { margin-top: 20px; text-align: center; }
    .sm-footer a { font-size: 12px; font-weight: 600; color: #64748b; text-decoration: none; }
    .sm-footer a:hover { color: #0f172a; }

    @media (max-width: 1024px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .bottom-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .stats-row { grid-template-columns: 1fr; }
    }
</style>

<div class="dashboard-container">
    <div class="dash-header">
        <div class="dh-left">
            <div class="dh-sub">EXECUTIVE OVERVIEW</div>
            <h1 class="dh-title">Dashboard Utama</h1>
        </div>
        <div class="dh-right">
            <button class="btn-outline">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                7 Hari Terakhir
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <!-- Pendapatan -->
        <div class="stat-box">
            <div class="stat-box-top">
                <div class="sb-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                <div class="sb-trend up"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg> 12.4%</div>
            </div>
            <div class="sb-label">TOTAL PENDAPATAN</div>
            <div class="sb-value">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
        </div>
        
        <!-- Penjualan -->
        <div class="stat-box">
            <div class="stat-box-top">
                <div class="sb-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                <div class="sb-trend up"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg> 8.1%</div>
            </div>
            <div class="sb-label">TOTAL PENJUALAN</div>
            <div class="sb-value">{{ number_format($jumlahPenjualan, 0, ',', '.') }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">Transaksi</span></div>
        </div>

        <!-- Rata Pesanan -->
        <div class="stat-box">
            <div class="stat-box-top">
                <div class="sb-icon"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                <div class="sb-trend down"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/></svg> -2.3%</div>
            </div>
            <div class="sb-label">RATA RATA PESANAN</div>
            <div class="sb-value">Rp {{ number_format($rataRataPesanan, 0, ',', '.') }}</div>
        </div>

    </div>

    <!-- Chart -->
    <div class="chart-section">
        <div class="chart-header">
            <div>
                <div class="ch-title">Tren Penjualan Mingguan</div>
                <div class="ch-sub">Data real-time dari semua terminal aktif.</div>
            </div>
            <div class="ch-legend">
                <div class="legend-item"><div class="legend-dot blue"></div> Minggu Ini</div>
                <div class="legend-item"><div class="legend-dot gray"></div> Minggu Lalu</div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Bottom -->
    <div class="bottom-grid">
        <!-- Produk Terlaris -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Produk Terlaris</div>
                <a href="{{ route('admin.produk.index') }}" class="panel-link">Lihat Semua</a>
            </div>
            <table class="pt-table">
                <thead>
                    <tr>
                        <th>PRODUK</th>
                        <th>KATEGORI</th>
                        <th>TERJUAL</th>
                        <th>PENDAPATAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produkTerlaris as $pt)
                    <tr>
                        <td>
                            <div class="pt-product">
                                @if($pt->gambar)
                                    <img src="{{ asset('storage/' . $pt->gambar) }}" class="pt-img" alt="{{ $pt->nama_produk }}">
                                @else
                                    <div class="pt-img-fallback">No Img</div>
                                @endif
                                <span class="pt-name">{{ $pt->nama_produk }}</span>
                            </div>
                        </td>
                        <td><span class="pt-cat">{{ $pt->kategori->nama_kategori ?? '-' }}</span></td>
                        <td><span class="pt-qty">{{ $pt->terjual }} Unit</span></td>
                        <td><span class="pt-rev">Rp {{ number_format($pt->pendapatan, 0, ',', '.') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Stok Menipis -->
        <div class="panel">
            <div class="panel-header">
                <div class="panel-title">Stok Menipis</div>
                <div class="sm-badge">PERINGATAN</div>
            </div>
            <div class="sm-list">
                @forelse($produkHampirHabis as $produk)
                <div class="sm-item">
                    <div class="sm-left">
                        <div class="sm-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div class="sm-info">
                            <div class="sm-name">{{ $produk->nama_produk }}</div>
                            <div class="sm-stock">Sisa: <span>{{ $produk->stok }} Unit</span></div>
                        </div>
                    </div>
                    <a href="{{ route('admin.produk.index') }}" class="btn-order">Tambah Stok</a>
                </div>
                @empty
                <div style="padding: 30px; text-align: center; color: #94a3b8; font-size: 13px;">
                    Semua stok produk aman 👍
                </div>
                @endforelse
            </div>
            <div class="sm-footer">
                <a href="{{ route('admin.produk.index') }}">Lihat Inventaris Lengkap</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart');
    if(!ctx) return;
    
    // Parse PHP data to JS
    const chartData = @json($trenPenjualan);
    const labels = chartData.map(d => d.hari);
    const dataValues = chartData.map(d => d.total);
    
    // Create gradient
    let gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan',
                data: dataValues,
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#3b82f6',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, family: 'Inter' },
                    bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                    callbacks: {
                        label: function(context) {
                            let val = context.raw;
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { family: 'Inter', size: 11 }, color: '#64748b', padding: 10 }
                },
                y: {
                    display: false,
                    grid: { display: false },
                    beginAtZero: true
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>
@endsection

