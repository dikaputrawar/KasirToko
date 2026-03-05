<!-- Table Keranjang -->
<div class="overflow-x-auto bg-white rounded-xl border border-gray-200">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-sm">Kode</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 text-sm">Nama</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 text-sm">Harga</th>
                <th class="text-center py-3 px-4 font-semibold text-gray-600 text-sm w-24">Qty</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 text-sm">Subtotal</th>
                <th class="text-center py-3 px-4 font-semibold text-gray-600 text-sm w-12"></th>
            </tr>
        </thead>
        <tbody id="cart-body" class="divide-y divide-gray-200">
            @forelse($cart as $item)
                <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $item['id'] }}">
                    <td class="py-3 px-4 text-sm text-gray-800 font-medium">{{ $item['kode'] }}</td>
                    <td class="py-3 px-4 text-sm text-gray-800">{{ $item['nama'] }}</td>
                    <td class="py-3 px-4 text-sm text-gray-800 text-right">Rp{{ number_format($item['harga'],0,',','.') }}</td>
                    <td class="py-3 px-4">
                        <input type="number" min="1" max="{{ $item['stok'] }}" name="qty[]" value="{{ $item['qty'] }}" 
                               class="w-16 px-2 py-1 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 qty-input" 
                               data-id="{{ $item['id'] }}">
                    </td>
                    <td class="py-3 px-4 text-sm text-gray-800 text-right font-medium subtotal">Rp{{ number_format($item['qty'] * $item['harga'],0,',','.') }}</td>
                    <td class="py-3 px-4 text-center">
                        <button type="button" class="text-gray-400 hover:text-red-600 transition-colors btn-remove" data-id="{{ $item['id'] }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span>Belum ada barang di keranjang</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>