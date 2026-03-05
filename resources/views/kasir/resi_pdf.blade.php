<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resi Transaksi #{{ $transaksi->id }}</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: monospace;
            font-size: 10px;
        }
        pre {
            margin: 0;
            padding: 0;
            font-family: monospace;
            font-size: 10px;
            line-height: 1.2;
        }
    </style>
</head>
<body>
<pre>
@php
function rupiah($angka) {
    return number_format($angka, 0, ',', '.');
}

function line($left, $right = '') {
    $width = 30; // kurangi dari 32 ke 30 untuk geser ke kanan
    $space = $width - strlen($left) - strlen($right);
    return '  ' . $left . str_repeat(' ', max($space, 0)) . $right; // pastikan tidak negatif
}

function center($text) {
    $width = 30; // kurangi dari 32 ke 30
    $padding = ($width - strlen($text)) / 2;
    return '  ' . str_repeat(' ', max(floor($padding), 0)) . $text; // pastikan tidak negatif
}
@endphp
--------------------------------
{{ center('WARUNGKU') }}
{{ center('Jl. Contoh No.123') }}
{{ center('Telp: 08123456789') }}
--------------------------------
{{ line('No', ': #' . $transaksi->id) }}
{{ line('Tanggal', ': ' . $transaksi->created_at->format('d/m/Y H:i')) }}
{{ line('Kasir', ': ' . ($transaksi->user->name ?? 'Admin')) }}
--------------------------------
@foreach($transaksi->details as $detail)
{{ $detail->barang->nama }}
{{ line($detail->qty . ' x ' . rupiah($detail->harga_saat_transaksi), rupiah($detail->subtotal)) }}
@if (!$loop->last)
--------------------------------
@endif
@endforeach
--------------------------------
{{ line('Total', rupiah($transaksi->total)) }}
{{ line('Bayar', rupiah($transaksi->bayar)) }}
{{ line('Kembali', rupiah($transaksi->kembalian)) }}
--------------------------------
{{ center('Terima kasih') }}
{{ center('Barang tidak dapat dikembalikan') }}
</pre>
</body>
</html>
