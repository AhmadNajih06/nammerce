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
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider"># Order</th>
                                <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-3 font-medium text-indigo-600">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">
                                        {{ $order->order_date->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-5 py-3 font-semibold text-gray-800">
                                        {{ $order->formattedTotal() }}
                                    </td>
                                    <td class="px-5 py-3">
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
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('pelanggan.orders.show', $order) }}"
                                           class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">
                                            Detail &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if ($orders->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
