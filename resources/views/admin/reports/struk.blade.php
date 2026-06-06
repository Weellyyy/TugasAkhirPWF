<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi #{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt {
            background-color: #fff;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
        .info {
            font-size: 12px;
            margin-bottom: 15px;
        }
        .info table {
            width: 100%;
        }
        .items {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .items td {
            padding: 5px 0;
            vertical-align: top;
        }
        .items .qty {
            width: 30px;
            text-align: center;
        }
        .items .price {
            text-align: right;
        }
        .totals {
            width: 100%;
            font-size: 12px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-bottom: 20px;
        }
        .totals td {
            padding: 2px 0;
        }
        .totals .label {
            font-weight: bold;
        }
        .totals .amount {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #2563eb;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-weight: bold;
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .receipt {
                box-shadow: none;
                width: 100%;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h2>POS SYSTEM</h2>
            <p>Struk Pembelian</p>
        </div>
        
        <div class="info">
            <table>
                <tr>
                    <td>Waktu</td>
                    <td>: {{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>No. TRX</td>
                    <td>: TRX-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: {{ $transaksi->kasir->nama ?? 'Sistem' }}</td>
                </tr>
                <tr>
                    <td>Metode</td>
                    <td>: {{ strtoupper($transaksi->metode_pembayaran) }}</td>
                </tr>
            </table>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="qty">Qty</th>
                    <th class="price">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->detail as $d)
                <tr>
                    <td>{{ $d->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                    <td class="qty">{{ $d->jumlah }}</td>
                    <td class="price">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <td class="label">TOTAL</td>
                <td class="amount">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">BAYAR</td>
                <td class="amount">Rp {{ number_format($transaksi->jumlah_bayar ?? $transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
            @if(isset($transaksi->kembalian))
            <tr>
                <td class="label">KEMBALI</td>
                <td class="amount">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>

        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>

        <button class="btn-print" onclick="window.print()">Cetak Struk</button>
    </div>
    
    <script>
        // Otomatis memunculkan dialog print saat pop-up dimuat
        window.onload = function() { 
            window.print(); 
        }
    </script>
</body>
</html>
