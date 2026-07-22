<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pelanggan.orders.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan <span class="text-indigo-600">#{{ $order->id }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info order --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1 text-sm">
                    <div class="text-gray-500">
                        Tanggal pesan:
                        <span class="font-medium text-gray-800">
                            {{ $order->order_date->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <div class="text-gray-500">
                        Total pembayaran:
                        <span class="font-bold text-gray-900 text-base">{{ $order->formattedTotal() }}</span>
                    </div>
                </div>

                {{-- Badge status --}}
                @php
                    $badge = match($order->status) {
                        'pending'    => 'bg-yellow-100 text-yellow-700',
                        'processing' => 'bg-blue-100 text-blue-700',
                        'completed'  => 'bg-green-100 text-green-700',
                        'cancelled'  => 'bg-red-100 text-red-700',
                        default      => 'bg-gray-100 text-gray-700',
                    };
                    $icon = match($order->status) {
                        'pending'    => '🕐',
                        'processing' => '📦',
                        'completed'  => '✅',
                        'cancelled'  => '❌',
                        default      => '•',
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold {{ $badge }}">
                    {{ $icon }} {{ $order->statusLabel() }}
                </span>
            </div>

            {{-- Item pesanan --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Item Pesanan</h3>
                </div>

                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product?->imageUrl() ?? asset('images/no-image.png') }}"
                                             alt="{{ $item->product?->name }}"
                                             class="w-10 h-10 object-cover rounded border border-gray-200 flex-shrink-0">
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $item->product?->name ?? '(produk tidak tersedia)' }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $item->product?->category?->name }}
                                            </p>
                                        </div>
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
            </div>

            {{-- Aksi --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('pelanggan.orders.index') }}"
                   class="px-5 py-2 border border-gray-300 text-sm text-gray-700 rounded-md hover:bg-gray-50 transition">
                    &larr; Semua Pesanan
                </a>
                <a href="{{ route('pelanggan.products.index') }}"
                   class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Lanjut Belanja
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
