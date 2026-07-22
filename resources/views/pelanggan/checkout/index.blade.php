<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pelanggan.cart.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Konfirmasi Pesanan</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan produk --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Ringkasan Pesanan</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($items as $item)
                            <tr>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/no-image.png') }}"
                                             alt="{{ $item['name'] }}"
                                             class="w-10 h-10 object-cover rounded border border-gray-200 flex-shrink-0">
                                        <span class="font-medium text-gray-800">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-right text-gray-600">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-right text-gray-600">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="px-6 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">Total</td>
                            <td class="px-6 py-4 text-right font-bold text-lg text-gray-900">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Info pembeli --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">Data Pemesan</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <div><span class="font-medium text-gray-700">Nama:</span> {{ auth()->user()->name }}</div>
                    <div><span class="font-medium text-gray-700">Email:</span> {{ auth()->user()->email }}</div>
                </div>
            </div>

            {{-- Tombol konfirmasi --}}
            <form action="{{ route('pelanggan.checkout.store') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('pelanggan.cart.index') }}"
                       class="px-5 py-2 border border-gray-300 text-sm text-gray-700 rounded-md hover:bg-gray-50 transition">
                        &larr; Kembali ke Keranjang
                    </a>
                    <button type="submit"
                            class="px-8 py-2.5 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
                        Konfirmasi &amp; Pesan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
