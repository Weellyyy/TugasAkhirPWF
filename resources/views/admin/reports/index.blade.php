@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
@php
    $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp

<div class="print-header-report hidden mb-6 text-center">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Penjualan (POS Master)</h1>
    <p class="text-sm text-gray-500">
        Periode: 
        @if($filterType === 'hari') 
            Hari Ini 
        @elseif($filterType === 'minggu') 
            Minggu Ini 
        @elseif($filterType === 'bulan') 
            Bulan {{ $bulanIndo[$filterMonth] ?? $filterMonth }} {{ $filterYear }}
        @elseif($filterType === 'tahun') 
            Tahun {{ $filterYear }}
        @else 
            Semua Penjualan 
        @endif
    </p>
    <p class="text-xs text-gray-400 mt-1">Dicetak pada: <span id="print-timestamp"></span></p>
</div>

<!-- Filters & Export/Print Actions -->
<div class="mb-6 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100 no-print w-full">
    <form id="filter-form" action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
            <label for="filter_type" class="text-xs font-bold text-gray-700">Filter:</label>
            <select name="filter_type" id="filter_type" onchange="toggleFilterInputs()" class="shadow-sm border border-gray-300 rounded-lg py-1.5 px-3 text-xs text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="semua" {{ $filterType === 'semua' ? 'selected' : '' }}>Semua Penjualan</option>
                <option value="hari" {{ $filterType === 'hari' ? 'selected' : '' }}>Hari Ini</option>
                <option value="minggu" {{ $filterType === 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="bulan" {{ $filterType === 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                <option value="tahun" {{ $filterType === 'tahun' ? 'selected' : '' }}>Per Tahun</option>
            </select>
        </div>

        <!-- Month Dropdown Container -->
        <div id="month_wrapper" class="flex items-center gap-2 {{ $filterType === 'bulan' ? '' : 'hidden' }}">
            <label for="filter_month" class="text-xs font-bold text-gray-700">Bulan:</label>
            <select name="filter_month" id="filter_month" onchange="document.getElementById('filter-form').submit()" class="shadow-sm border border-gray-300 rounded-lg py-1.5 px-3 text-xs text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                @foreach($bulanIndo as $num => $name)
                    <option value="{{ $num }}" {{ $filterMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Year Dropdown Container -->
        <div id="year_wrapper" class="flex items-center gap-2 {{ ($filterType === 'bulan' || $filterType === 'tahun') ? '' : 'hidden' }}">
            <label for="filter_year" class="text-xs font-bold text-gray-700">Tahun:</label>
            <select name="filter_year" id="filter_year" onchange="document.getElementById('filter-form').submit()" class="shadow-sm border border-gray-300 rounded-lg py-1.5 px-3 text-xs text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                @for($y = date('Y') - 2; $y <= date('Y') + 5; $y++)
                    <option value="{{ $y }}" {{ $filterYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </form>
    
    <div class="flex items-center gap-2">
        <button onclick="printLaporan()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-1.5 px-4 rounded-lg border border-slate-200 transition duration-200 flex items-center gap-1.5 text-xs cursor-pointer">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Laporan
        </button>
        <a href="{{ route('admin.reports.index', ['filter_type' => $filterType, 'filter_month' => $filterMonth, 'filter_year' => $filterYear, 'export' => 'csv']) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-4 rounded-lg shadow-sm transition duration-200 flex items-center gap-1.5 text-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Export Excel (CSV)
        </a>
    </div>
</div>

<div class="mb-4 reports-print-element">
    <h2 class="text-xl font-semibold text-gray-800">Detail Transaksi Penjualan</h2>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden reports-print-element">
    <div class="p-4 border-b border-gray-200 bg-gray-50 no-print">
        <p class="text-sm text-gray-600">
            Menampilkan data transaksi penjualan periode: 
            <strong>
                @if($filterType === 'hari') 
                    Hari Ini 
                @elseif($filterType === 'minggu') 
                    Minggu Ini 
                @elseif($filterType === 'bulan') 
                    Bulan {{ $bulanIndo[$filterMonth] ?? $filterMonth }} {{ $filterYear }}
                @elseif($filterType === 'tahun') 
                    Tahun {{ $filterYear }}
                @else 
                    Semua Penjualan 
                @endif
            </strong>.
        </p>
    </div>
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail Produk (Qty x Harga)</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Metode</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $t)
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm whitespace-nowrap">
                    {{ $t->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $t->kasir->nama ?? 'Unknown' }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($t->detail as $d)
                        <li>
                            {{ $d->produk->nama_produk ?? 'Produk Dihapus' }} 
                            <span class="text-gray-500">({{ $d->jumlah }} x Rp {{ number_format($d->harga, 0, ',', '.') }})</span>
                            = Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    {{ $t->metode_pembayaran }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-900">
                    Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                    <button onclick="showReceipt({{ $t->id }})" class="bg-teal-50 hover:bg-teal-100 text-teal-600 font-semibold py-1.5 px-3 rounded border border-teal-200 transition duration-200 text-xs">
                        Lihat Struk
                    </button>
                </td>
            </tr>
            @endforeach
            @if($transaksis->isEmpty())
            <tr>
                <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    Belum ada transaksi.
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

@foreach($transaksis as $t)
    <!-- Receipt Modal for TRX-{{ $t->id }} -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden" id="receiptModal-{{ $t->id }}">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-[420px] flex flex-col relative border border-gray-100 overflow-hidden mx-4 text-left">
            <!-- Close Button (X) -->
            <button onclick="hideReceipt({{ $t->id }})" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-[#f1f5f9] hover:bg-[#e2e8f0] flex items-center justify-center text-slate-500 hover:text-slate-700 transition duration-200 z-20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Printable Area -->
            <div id="printable-receipt-{{ $t->id }}" class="bg-white font-sans text-gray-800 p-8 pb-5">
                <!-- Store Header -->
                <div class="text-center mt-3 mb-5">
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">POS Master</h2>
                    <p class="text-xs text-slate-400 mt-1">Jl. Sudirman No. 123, Jakarta Raya</p>
                    <p class="text-xs text-slate-400">Telp: 021-555-0198</p>
                </div>

                <!-- Divider -->
                <div class="border-t border-dotted border-slate-300 my-4"></div>

                <!-- Meta Info -->
                <div class="flex flex-col gap-2.5 text-xs mb-4">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">No. Transaksi:</span>
                        <span class="font-bold text-slate-800">TRX-{{ sprintf('%04d', $t->id) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Tanggal:</span>
                        <span class="font-bold text-slate-800">{{ $t->created_at->format('j/n/Y, H.i.s') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Kasir:</span>
                        <span class="font-bold text-slate-800">{{ $t->kasir->nama ?? 'Unknown' }}</span>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-dotted border-slate-300 my-4"></div>

                <!-- Items List -->
                <div class="flex flex-col gap-4 mb-4">
                    @foreach($t->detail as $d)
                        <div class="flex flex-col">
                            <div class="flex justify-between font-bold text-slate-800 text-sm">
                                <span>{{ $d->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                <span>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5 font-medium">
                                {{ $d->jumlah }} x Rp {{ number_format($d->harga, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Divider -->
                <div class="border-t border-dotted border-slate-300 my-4"></div>

                <!-- Grand Total -->
                <div class="flex justify-between items-center py-1 mb-4">
                    <span class="font-extrabold text-slate-800 tracking-wide text-sm">GRAND TOTAL:</span>
                    <span class="text-xl font-black text-[#6366f1]">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span>
                </div>

                <!-- Payment Method Card -->
                <div class="bg-[#f8fafc] rounded-2xl p-4 flex justify-between items-center border border-[#f1f5f9] mb-5">
                    <span class="text-slate-400 text-xs font-semibold">Metode Pembayaran</span>
                    <span class="font-black text-[#6366f1] text-xs">
                        {{ $t->metode_pembayaran === 'Cash' ? 'Tunai / Cash' : 'QRIS' }}
                    </span>
                </div>

                <!-- Divider -->
                <div class="border-t border-dotted border-slate-300 my-4"></div>

                <!-- Footer -->
                <div class="text-center text-xs text-slate-400 font-medium py-2">
                    <p class="mb-1">Salinan Struk Transaksi</p>
                    <p>Terima kasih atas kunjungan Anda.</p>
                </div>
            </div>

            <!-- Action Button at Bottom -->
            <div class="bg-[#f8fafc] p-6 border-t border-slate-100 no-print">
                <button onclick="printReceipt({{ $t->id }})" class="w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-extrabold py-3.5 px-4 rounded-2xl shadow-sm transition duration-200 flex items-center justify-center gap-2 text-xs tracking-wider">
                    <!-- Printer Icon -->
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    CETAK ULANG STRUK
                </button>
            </div>
        </div>
    </div>
@endforeach

<style>
    @media print {
        html, body {
            height: auto !important;
            overflow: visible !important;
            position: static !important;
            background: white !important;
        }
        .bg-gray-900, header, .no-print {
            display: none !important;
        }
        
        /* Hide reports content if printing a receipt */
        body.printing-receipt .reports-print-element,
        body.printing-receipt .print-header-report {
            display: none !important;
        }
        
        /* If printing a receipt, format the active modal */
        .print-modal-active {
            display: block !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: white !important;
            z-index: 9999 !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .print-modal-active > div {
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            border-radius: 0 !important;
        }
        .print-receipt-active {
            box-shadow: none !important;
            border: none !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* If printing the report itself, format it nicely */
        body:not(.printing-receipt) .print-header-report {
            display: block !important;
            margin-bottom: 20px !important;
        }
        body:not(.printing-receipt) .reports-print-element {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
        }
        body:not(.printing-receipt) table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        body:not(.printing-receipt) th, 
        body:not(.printing-receipt) td {
            border: 1px solid #cbd5e1 !important;
        }
        /* Hide the actions column in report print */
        body:not(.printing-receipt) th:last-child,
        body:not(.printing-receipt) td:last-child {
            display: none !important;
        }
    }
    
    .print-header-report {
        display: none;
    }
</style>

<script>
    function toggleFilterInputs() {
        const filterType = document.getElementById('filter_type').value;
        const monthWrapper = document.getElementById('month_wrapper');
        const yearWrapper = document.getElementById('year_wrapper');

        if (filterType === 'bulan') {
            monthWrapper.classList.remove('hidden');
            yearWrapper.classList.remove('hidden');
        } else if (filterType === 'tahun') {
            monthWrapper.classList.add('hidden');
            yearWrapper.classList.remove('hidden');
        } else {
            // Semua/Hari/Minggu: langsung submit tanpa perlu pilih bulan/tahun
            monthWrapper.classList.add('hidden');
            yearWrapper.classList.add('hidden');
            document.getElementById('filter-form').submit();
            return;
        }
        // Untuk bulan/tahun: tunggu user pilih nilai bulan/tahun dulu (auto-submit di dropdown masing-masing)
    }

    function showReceipt(id) {
        document.getElementById('receiptModal-' + id).classList.remove('hidden');
    }

    function hideReceipt(id) {
        document.getElementById('receiptModal-' + id).classList.add('hidden');
    }

    function printReceipt(id) {
        const modal = document.getElementById('receiptModal-' + id);
        const receiptContent = document.getElementById('printable-receipt-' + id);
        
        // Hide reports content from print layout
        document.body.classList.add('printing-receipt');
        
        modal.classList.add('print-modal-active');
        receiptContent.classList.add('print-receipt-active');
        
        window.print();
        
        setTimeout(() => {
            modal.classList.remove('print-modal-active');
            receiptContent.classList.remove('print-receipt-active');
            document.body.classList.remove('printing-receipt');
        }, 500);
    }
    function printLaporan() {
        // Ambil nilai filter yang sedang dipilih di dropdown
        const filterType  = document.getElementById('filter_type').value;
        const filterMonth = document.getElementById('filter_month')?.value ?? '';
        const filterYear  = document.getElementById('filter_year')?.value ?? '';

        // Bangun URL dengan filter aktif + flag autoprint
        const url = new URL(window.location.href);
        url.searchParams.set('filter_type', filterType);
        url.searchParams.set('filter_month', filterMonth);
        url.searchParams.set('filter_year', filterYear);
        url.searchParams.delete('export');
        url.searchParams.set('autoprint', '1');

        // Redirect ke halaman dengan data ter-filter, lalu auto-print
        window.location.href = url.toString();
    }

    // Auto-print saat halaman dimuat dengan flag ?autoprint=1
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        if (params.get('autoprint') === '1') {
            // Isi timestamp cetak dengan waktu sekarang
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            const tgl = pad(now.getDate()) + '/' + pad(now.getMonth() + 1) + '/' + now.getFullYear();
            const jam = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            const el = document.getElementById('print-timestamp');
            if (el) el.textContent = tgl + ' ' + jam;

            // Hapus flag dari URL (biar tidak loop) lalu print
            const cleanUrl = new URL(window.location.href);
            cleanUrl.searchParams.delete('autoprint');
            window.history.replaceState({}, '', cleanUrl.toString());

            // Tunda sedikit agar halaman selesai render
            setTimeout(() => window.print(), 400);
        }
    });
</script>
@endsection
