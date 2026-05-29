@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Detail Transaksi Penjualan</h2>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <p class="text-sm text-gray-600">Menampilkan semua data transaksi penjualan.</p>
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
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm no-print hidden" id="receiptModal-{{ $t->id }}">
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
        body * {
            visibility: hidden !important;
        }
        .print-receipt-active, .print-receipt-active * {
            visibility: visible !important;
        }
        .print-receipt-active {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<script>
    function showReceipt(id) {
        document.getElementById('receiptModal-' + id).classList.remove('hidden');
    }

    function hideReceipt(id) {
        document.getElementById('receiptModal-' + id).classList.add('hidden');
    }

    function printReceipt(id) {
        const receiptContent = document.getElementById('printable-receipt-' + id);
        receiptContent.classList.add('print-receipt-active');
        window.print();
        setTimeout(() => {
            receiptContent.classList.remove('print-receipt-active');
        }, 500);
    }
</script>
@endsection
