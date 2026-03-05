@extends('layouts.dashboard')

@section('title', 'Kasir')
@section('subtitle', 'Input barang dan proses transaksi')

@section('content')
<div class="max-w-7xl mx-auto mt-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Layout 2 Kolom -->
    <div class="flex gap-6">
        <!-- Kiri: Input Barang + Keranjang -->
        <div class="flex-1 space-y-6">
            <!-- Card Input Barang -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Input Barang</h3>
                <form id="form-scan" autocomplete="off">
                    <div class="flex gap-3">
                        <input type="text" id="kode" name="kode" class="flex-1 px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Scan / Input Kode Barang" autofocus>
                        <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                            <i class="fas fa-plus mr-2"></i>Tambah
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Keranjang -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Keranjang</h3>
                <div id="kasir-cart-area">
                    @include('kasir._cart', ['cart' => $cart, 'total' => $total])
                </div>
            </div>
        </div>

        <!-- Kanan: Pembayaran + Barang Populer -->
        <div class="w-96 space-y-6">
            <!-- Card Pembayaran -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h3>
                <form id="form-transaksi" method="POST" action="{{ route('kasir.simpan') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Total</label>
                            <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            <input type="hidden" id="total" name="total" value="{{ $total }}">
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Bayar</label>
                            <input type="number" id="bayar" name="bayar" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="0">
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Kembalian</label>
                            <div class="text-xl font-semibold text-emerald-600">Rp <span id="kembalian-display">0</span></div>
                            <input type="hidden" id="kembalian" name="kembalian" value="0">
                        </div>
                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-semibold">
                            <i class="fas fa-save mr-2"></i>Simpan Transaksi
                        </button>
                        <button type="button" class="w-full py-3 border border-emerald-600 text-emerald-600 rounded-xl hover:bg-emerald-50 transition font-semibold">
                            <i class="fas fa-print mr-2"></i>Cetak Struk
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card Barang Populer -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Barang Terlaris Minggu Ini</h3>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($barangPopuler as $barang)
                        @if($barang->barang)
                            <div class="p-3 border rounded-xl hover:bg-gray-50 cursor-pointer transition-colors item-populer"
                                 data-id="{{ $barang->barang->id }}"
                                 data-kode="{{ $barang->barang->kode }}"
                                 data-nama="{{ $barang->barang->nama }}"
                                 data-harga="{{ $barang->barang->harga }}"
                                 data-stok="{{ $barang->barang->stok }}">
                                <div class="text-xs text-gray-500">{{ $barang->barang->kode }}</div>
                                <div class="text-sm font-medium text-gray-800">{{ $barang->barang->nama }}</div>
                                <div class="text-xs text-green-600 font-semibold">Rp{{ number_format($barang->barang->harga,0,',','.') }}</div>
                                <div class="text-xs text-gray-400 mt-1">Terjual: {{ $barang->total_qty }} pcs</div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-2 text-center text-gray-500 py-4">
                            <div class="text-sm">Belum ada data penjualan minggu ini</div>
                        </div>
                    @endforelse
                </div>
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
            updateTotalDisplay();
        }).fail(function(xhr) {
            alert(xhr.responseJSON.error || 'Gagal scan barang');
        });
        $('#kode').val('').focus();
    });
    
    function updateTotalDisplay() {
        // Update total display di panel pembayaran
        let total = 0;
        $('.subtotal').each(function() {
            let subtotalText = $(this).text().replace('Rp', '').replace(/\./g, '');
            total += parseInt(subtotalText) || 0;
        });
        $('#total').val(total);
        $('.text-2xl.font-bold.text-gray-800').text('Rp ' + total.toLocaleString('id-ID'));
        
        // Reset kembalian saat total berubah
        let bayar = parseInt($('#bayar').val()) || 0;
        let kembalian = bayar - total;
        $('#kembalian').val(kembalian > 0 ? kembalian : 0);
        $('#kembalian-display').text(kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0);
    }
    
    function attachCartEvents() {
        $('.qty-input').off('change').on('change', function() {
            let id = $(this).data('id');
            let qty = $(this).val();
            $.post("{{ route('kasir.updateQty') }}", {id: id, qty: qty, _token: '{{ csrf_token() }}'}, function(res) {
                $('#kasir-cart-area').html(res.html);
                attachCartEvents();
                updateTotalDisplay();
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
                updateTotalDisplay();
            });
        });
        $('#bayar').off('input').on('input', function() {
            let total = parseInt($('#total').val()) || 0;
            let bayar = parseInt($(this).val()) || 0;
            let kembalian = bayar - total;
            $('#kembalian').val(kembalian > 0 ? kembalian : 0);
            $('#kembalian-display').text(kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0);
        });
    }
    attachCartEvents();
    updateTotalDisplay();
    
    $('#kode').on('keydown', function(e) {
        if (e.key === 'Enter') {
            $('#form-scan').submit();
        }
    });
    
    // Cetak Struk functionality
    $('.border-emerald-600').on('click', function() {
        window.print();
    });
});
</script>
@endpush