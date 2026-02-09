<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Transaksi #{{ $transaksi->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            background: white;
        }
        .receipt {
            width: 300px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 10px;
        }
        .transaction-info {
            margin-bottom: 10px;
        }
        .transaction-info p {
            margin: 2px 0;
        }
        .items {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .item-name {
            flex: 1;
        }
        .item-qty {
            text-align: center;
            margin: 0 5px;
        }
        .item-price {
            text-align: right;
            margin-left: 5px;
        }
        .total-section {
            text-align: right;
            font-weight: bold;
        }
        .total-section p {
            margin: 2px 0;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .receipt {
                border: none;
                width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>WARUNGKU</h1>
            <p>Jl. Contoh No. 123</p>
            <p>Telp: (021) 1234-5678</p>
            <p>{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="transaction-info">
            <p><strong>No. Transaksi:</strong> #{{ $transaksi->id }}</p>
            <p><strong>Kasir:</strong> {{ $transaksi->user->name ?? 'Admin' }}</p>
        </div>

        <div class="items">
            @foreach($transaksi->details as $detail)
            <div class="item">
                <div class="item-name">{{ $detail->barang->nama }}</div>
                <div class="item-qty">{{ $detail->qty }}x</div>
                <div class="item-price">{{ number_format($detail->harga_saat_transaksi) }}</div>
            </div>
            <div class="item">
                <div class="item-name">{{ $detail->barang->kode }}</div>
                <div class="item-qty"></div>
                <div class="item-price">{{ number_format($detail->subtotal) }}</div>
            </div>
            @endforeach
        </div>

        <div class="total-section">
            <p>Total: Rp {{ number_format($transaksi->total) }}</p>
            <p>Bayar: Rp {{ number_format($transaksi->bayar) }}</p>
            <p>Kembalian: Rp {{ number_format($transaksi->kembalian) }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah berbelanja</p>
            <p>di WarungKu</p>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Cetak Resi
        </button>
        <a href="{{ route('kasir.index') }}" style="display: inline-block; margin-left: 10px; padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
            Kembali ke Kasir
        </a>
    </div>
</body>
</html> 