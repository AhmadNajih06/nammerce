<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Order <span class="text-indigo-600"></span>
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Baris atas: Status + Update ── --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    {{-- Info ringkas --}}
                    <div class="space-y-1 text-sm">
                        <div class="text-gray-500">
                            Tanggal:
                            <span class="font-medium text-gray-800">
                                {{ $order->order_date->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        <div class="text-gray-500">
                            Total:
                            <span class="font-bold text-gray-900 text-base">{{ $order->formattedTotal() }}</span>
                        </div>
                        <div class="flex items-center gap-2 pt-1">
                            @php
                                $badge = match($order->status) {
                                    'pending'    => 'bg-yellow-100 text-yellow-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'completed'  => 'bg-green-100 text-green-700',
                                    'cancelled'  => 'bg-red-100 text-red-700',
                                    default      => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                {{ $order->statusLabel() }}
                            </span>
                            @if ($order->status === 'completed')
                                <span class="text-xs text-gray-400 italic">Order selesai — status tidak dapat diubah</span>
                            @endif
                        </div>
                    </div>

                    {{-- Form update status — dikunci jika completed --}}
                    @if ($order->status !== 'completed')
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                    class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach ([
                                    'pending'    => 'Menunggu',
                                    'processing' => 'Diproses',
                                    'completed'  => 'Selesai',
                                    'cancelled'  => 'Dibatalkan',
                                ] as $val => $label)
                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                Perbarui Status
                            </button>
                        </form>
                    @endif

                </div>
            </div>

            {{-- ── Tiga card info: Pelanggan | Pengiriman | Pembayaran ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Profil Pelanggan --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-5 space-y-3">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelanggan</h4>
                    <div class="flex items-center gap-3">
                        <img src="{{ $order->user->avatarUrl() }}"
                             alt="{{ $order->user->name }}"
                             class="w-10 h-10 rounded-full object-cover border border-gray-200 flex-shrink-0">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $order->user->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- Informasi Pengiriman --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-5 space-y-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi Pengiriman</h4>
                    @if ($order->shipping_name)
                        <div class="text-sm space-y-1">
                            <div class="flex gap-2">
                                <span class="text-gray-400 w-20 flex-shrink-0">Penerima</span>
                                <span class="text-gray-800 font-medium">{{ $order->shipping_name }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-gray-400 w-20 flex-shrink-0">Telepon</span>
                                <span class="text-gray-800">{{ $order->shipping_phone }}</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="text-gray-400 w-20 flex-shrink-0">Alamat</span>
                                <span class="text-gray-800 leading-snug">{{ $order->shipping_address }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-gray-400 italic">Tidak ada data pengiriman</p>
                    @endif
                </div>

                {{-- Metode Pembayaran --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-5 space-y-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembayaran</h4>
                    @if ($order->payment_method)
                        @php
                            $payLabel = match($order->payment_method) {
                                'transfer' => ['icon' => '🏦', 'label' => 'Transfer Bank',          'desc' => 'BCA / Mandiri / BNI / BRI'],
                                'ewallet'  => ['icon' => '📱', 'label' => 'E-Wallet',               'desc' => 'GoPay / OVO / DANA / ShopeePay'],
                                'cod'      => ['icon' => '🚚', 'label' => 'Bayar di Tempat (COD)', 'desc' => 'Bayar saat paket tiba'],
                                default    => ['icon' => '💳', 'label' => ucfirst($order->payment_method), 'desc' => ''],
                            };
                        @endphp
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $payLabel['icon'] }} {{ $payLabel['label'] }}
                        </p>
                        @if ($payLabel['desc'])
                            <p class="text-xs text-gray-400">{{ $payLabel['desc'] }}</p>
                        @endif
                    @else
                        <p class="text-xs text-gray-400 italic">Tidak ada data pembayaran</p>
                    @endif
                </div>

            </div>

            {{-- ── Item Pesanan ── --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Item Pesanan</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
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
                                             alt="{{ $item->product?->name }}"
                                             class="w-10 h-10 object-cover rounded border border-gray-200 flex-shrink-0">
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $item->product?->name ?? '(produk dihapus)' }}
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

        </div>
    </div>
</x-app-layout>
