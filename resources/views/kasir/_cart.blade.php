<div class="table-responsive mb-3">
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Harga</th>
                <th width="90">Qty</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="cart-body">
            @forelse($cart as $item)
            <tr data-id="{{ $item['id'] }}">
                <td>{{ $item['kode'] }}</td>
                <td>{{ $item['nama'] }}</td>
                <td>Rp{{ number_format($item['harga'],0,',','.') }}</td>
                <td><input type="number" min="1" max="{{ $item['stok'] }}" name="qty[]" value="{{ $item['qty'] }}" class="form-control form-control-sm qty-input" data-id="{{ $item['id'] }}"></td>
                <td class="subtotal">Rp{{ number_format($item['qty'] * $item['harga'],0,',','.') }}</td>
                <td><button type="button" class="btn btn-danger btn-sm btn-remove" data-id="{{ $item['id'] }}">&times;</button></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Belum ada barang</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="row mb-2">
    <div class="col-md-6">
        <div class="input-group mb-2">
            <span class="input-group-text">Total</span>
            <input type="text" id="total" class="form-control" value="{{ $total }}" readonly>
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text">Bayar</span>
            <input type="number" min="0" id="bayar" name="bayar" class="form-control" required>
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text">Kembalian</span>
            <input type="text" id="kembalian" class="form-control" readonly>
        </div>
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <button type="submit" class="btn btn-success w-100 btn-lg">Simpan Transaksi</button>
    </div>
</div> 