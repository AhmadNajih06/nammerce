<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Sambutan --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    Selamat datang, {{ auth()->user()->name }}!
                </h3>
                <p class="text-sm text-gray-500">Anda login sebagai <span class="font-medium text-indigo-600">Admin</span>.</p>
            </div>

            {{-- Stat cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Total Order</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="mt-2 inline-block text-xs text-indigo-600 hover:text-indigo-800">Lihat semua &rarr;</a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Order Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ number_format($pendingOrders) }}</p>
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="mt-2 inline-block text-xs text-indigo-600 hover:text-indigo-800">Lihat pending &rarr;</a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Total Penjualan</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-gray-400">Tidak termasuk order dibatalkan</p>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-5">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Total Produk</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                    <a href="{{ route('admin.products.index') }}" class="mt-2 inline-block text-xs text-indigo-600 hover:text-indigo-800">Kelola produk &rarr;</a>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Order terbaru --}}
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">Order Terbaru</h3>
                        <a href="{{ route('admin.orders.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">Lihat semua &rarr;</a>
                    </div>
                    @if ($recentOrders->isEmpty())
                        <p class="px-6 py-8 text-sm text-gray-400 text-center">Belum ada order.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($recentOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.orders.show', $order) }}"
                                               class="font-medium text-indigo-600 hover:text-indigo-800">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">{{ $order->user->name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $order->formattedTotal() }}</td>
                                        <td class="px-4 py-3">
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Produk terlaris --}}
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Produk Terlaris</h3>
                    </div>
                    @if ($topProducts->isEmpty())
                        <p class="px-6 py-8 text-sm text-gray-400 text-center">Belum ada data penjualan.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Terjual</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($topProducts as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $item->product?->imageUrl() ?? asset('images/no-image.png') }}"
                                                     alt="{{ $item->product?->name }}"
                                                     class="w-9 h-9 object-cover rounded border border-gray-200 flex-shrink-0">
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $item->product?->name ?? '(dihapus)' }}</p>
                                                    <p class="text-xs text-gray-400">{{ $item->product?->category?->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            {{ number_format($item->total_qty) }} pcs
                                        </td>
                                        <td class="px-4 py-3 text-right text-green-600 font-semibold">
                                            Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>

            {{-- Menu navigasi cepat --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <a href="{{ route('admin.orders.index') }}"
                   class="bg-white shadow-sm sm:rounded-lg p-5 hover:shadow-md transition group">
                    <h4 class="font-semibold text-gray-800 mb-1 group-hover:text-indigo-600">Kelola Order</h4>
                    <p class="text-sm text-gray-500">Lihat & perbarui status pesanan pelanggan.</p>
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="bg-white shadow-sm sm:rounded-lg p-5 hover:shadow-md transition group">
                    <h4 class="font-semibold text-gray-800 mb-1 group-hover:text-indigo-600">Kelola Produk</h4>
                    <p class="text-sm text-gray-500">Tambah, ubah, dan hapus produk toko.</p>
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="bg-white shadow-sm sm:rounded-lg p-5 hover:shadow-md transition group">
                    <h4 class="font-semibold text-gray-800 mb-1 group-hover:text-indigo-600">Kelola Kategori</h4>
                    <p class="text-sm text-gray-500">Kelola kategori untuk produk toko.</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
