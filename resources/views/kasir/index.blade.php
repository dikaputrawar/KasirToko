@extends('layouts.app')

@section('title', 'Kasir - WarungKu')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">Transaksi Kasir - WarungKu</div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form id="form-scan" autocomplete="off">
                    <div class="input-group mb-3">
                        <input type="text" id="kode" name="kode" class="form-control form-control-lg" placeholder="Scan / Input Kode Barang" autofocus>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                <form id="form-transaksi" method="POST" action="{{ route('kasir.simpan') }}">
                    @csrf
                    <div id="kasir-cart-area">
                        @include('kasir._cart', ['cart' => $cart, 'total' => $total])
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(function() {
    $('#kode').focus();
    $('#form-scan').on('submit', function(e) {
        e.preventDefault();
        let kode = $('#kode').val();
        if (!kode) return;
        $.post("{{ route('kasir.scan') }}", {kode: kode, _token: '{{ csrf_token() }}'}, function(res) {
            $('#kasir-cart-area').html(res.html);
            attachCartEvents();
        }).fail(function(xhr) {
            alert(xhr.responseJSON.error || 'Gagal scan barang');
        });
        $('#kode').val('').focus();
    });
    function attachCartEvents() {
        $('.qty-input').off('change').on('change', function() {
            let id = $(this).data('id');
            let qty = $(this).val();
            $.post("{{ route('kasir.updateQty') }}", {id: id, qty: qty, _token: '{{ csrf_token() }}'}, function(res) {
                $('#kasir-cart-area').html(res.html);
                attachCartEvents();
            }).fail(function(xhr) {
                alert(xhr.responseJSON.error || 'Gagal update qty');
                location.reload();
            });
        });
        $('.btn-remove').off('click').on('click', function() {
            let id = $(this).data('id');
            $.post("{{ route('kasir.removeItem') }}", {id: id, _token: '{{ csrf_token() }}'}, function(res) {
                $('#kasir-cart-area').html(res.html);
                attachCartEvents();
            });
        });
        $('#bayar').off('input').on('input', function() {
            let total = parseInt($('#total').val()) || 0;
            let bayar = parseInt($(this).val()) || 0;
            let kembalian = bayar - total;
            $('#kembalian').val(kembalian > 0 ? kembalian : 0);
        });
    }
    attachCartEvents();
    $('#kode').on('keydown', function(e) {
        if (e.key === 'Enter') {
            $('#form-scan').submit();
        }
    });
});
</script>
@endpush 