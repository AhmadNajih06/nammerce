<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Pesanan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if ($orders->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-400 mb-3">Anda belum pernah melakukan pemesanan.</p>
                    <a href="{{ route('pelanggan.products.index') }}"
                       class="inline-block px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                        Mulai Belanja
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($orders as $order)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                            {{-- Header order --}}
                            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="font-medium text-gray-700">Order <span class="text-indigo-600"></span></span>
                                    <span class="text-gray-300">·</span>
                                    <span class="text-gray-500">{{ $order->order_date->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    @php
                                        $badge = match($order->status) {
                                            'pending'    => 'bg-yellow-100 text-yellow-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'completed'  => 'bg-green-100 text-green-700',
                                            'cancelled'  => 'bg-red-100 text-red-700',
                                            default      => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                        {{ $order->statusLabel() }}
                                    </span>
                                    <a href="{{ route('pelanggan.orders.show', $order) }}"
                                       class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>

                            {{-- Produk yang dibeli --}}
                            <div class="px-5 py-4">
                                <div class="flex flex-col gap-3">
                                    @foreach ($order->items as $item)
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item->product?->imageUrl() ?? asset('images/no-image.png') }}"
                                                 alt="{{ $item->product?->name }}"
                                                 class="w-14 h-14 object-cover rounded-lg border border-gray-100 flex-shrink-0">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-800 truncate">
                                                    {{ $item->product?->name ?? '(produk tidak tersedia)' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $item->formattedPrice() }} × {{ $item->quantity }}
                                                </p>
                                            </div>
                                            <p class="text-sm font-semibold text-gray-800 whitespace-nowrap">
                                                {{ $item->formattedSubtotal() }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Footer total --}}
                            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                                <span class="text-sm text-gray-500 mr-2">Total:</span>
                                <span class="text-sm font-bold text-gray-900">{{ $order->formattedTotal() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($orders->hasPages())
                    <div>{{ $orders->links() }}</div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
