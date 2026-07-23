<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pesanan Berhasil</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Banner sukses --}}
            <div class="bg-green-50 border border-green-200 sm:rounded-lg p-6 text-center">
                <div class="flex justify-center mb-3">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-green-800 mb-1">Pesanan Diterima!</h3>
                <p class="text-sm text-green-700">
                     <span class="font-semibold">Pesanan</span> telah berhasil dibuat.
                </p>
            </div>

            {{-- Ringkasan order --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Detail Pesanan</h3>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="text-gray-500">Status:</span>
                        @php
                            $badgeClass = match($order->status) {
                                'pending'    => 'bg-yellow-100 text-yellow-700',
                                'processing' => 'bg-blue-100 text-blue-700',
                                'completed'  => 'bg-green-100 text-green-700',
                                'cancelled'  => 'bg-red-100 text-red-700',
                                default      => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                            {{ $order->statusLabel() }}
                        </span>
                    </div>
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
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product?->imageUrl() ?? asset('images/no-image.png') }}"
                                             alt="{{ $item->product?->name ?? 'Produk' }}"
                                             class="w-10 h-10 object-cover rounded border border-gray-200 flex-shrink-0">
                                        <span class="font-medium text-gray-800">
                                            {{ $item->product?->name ?? '(produk dihapus)' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-right text-gray-600">
                                    {{ $item->formattedPrice() }}
                                </td>
                                <td class="px-6 py-3 text-right text-gray-600">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-3 text-right font-semibold text-gray-900">
                                    {{ $item->formattedSubtotal() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">Total</td>
                            <td class="px-6 py-4 text-right font-bold text-lg text-gray-900">
                                {{ $order->formattedTotal() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="px-6 py-4 border-t border-gray-100 text-xs text-gray-400">
                    Dipesan pada {{ $order->order_date->format('d M Y, H:i') }} WIB
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex justify-between">
                <a href="{{ route('pelanggan.products.index') }}"
                   class="px-5 py-2 border border-gray-300 text-sm text-gray-700 rounded-md hover:bg-gray-50 transition">
                    Lanjut Belanja
                </a>
                <a href="{{ route('pelanggan.orders.index') }}"
                   class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Lihat Riwayat Pesanan &rarr;
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
