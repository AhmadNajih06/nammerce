<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Order</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filter status --}}
            <div class="flex gap-2 flex-wrap">
                @foreach (['', 'pending', 'processing', 'completed', 'cancelled'] as $s)
                    <a href="{{ route('admin.orders.index', $s ? ['status' => $s] : []) }}"
                       class="px-3 py-1.5 text-sm rounded-md border transition
                              {{ request('status', '') === $s
                                  ? 'bg-indigo-600 text-white border-indigo-600'
                                  : 'bg-white text-gray-600 border-gray-300 hover:border-indigo-400' }}">
                        {{ match($s) {
                            ''           => 'Semua',
                            'pending'    => 'Menunggu',
                            'processing' => 'Diproses',
                            'completed'  => 'Selesai',
                            'cancelled'  => 'Dibatalkan',
                        } }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk Dibeli</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 align-top">
                                {{-- # Order --}}
                                

                                {{-- Profil pelanggan --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $order->user->avatarUrl() }}"
                                             alt="{{ $order->user->name }}"
                                             class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-gray-200">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-900 truncate">{{ $order->user->name }}</p>
                                            <p class="text-xs text-gray-400 truncate">{{ $order->user->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Produk yang dibeli --}}
                                <td class="px-4 py-4">
                                    <div class="flex flex-col gap-1.5">
                                        @foreach ($order->items->take(3) as $item)
                                            <div class="flex items-center gap-2">
                                                <img src="{{ $item->product?->imageUrl() ?? asset('images/no-image.png') }}"
                                                     alt="{{ $item->product?->name }}"
                                                     class="w-8 h-8 object-cover rounded border border-gray-100 flex-shrink-0">
                                                <span class="text-xs text-gray-700 truncate max-w-36">
                                                    {{ $item->product?->name ?? '(dihapus)' }}
                                                    <span class="text-gray-400">×{{ $item->quantity }}</span>
                                                </span>
                                            </div>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                            <p class="text-xs text-gray-400 pl-10">+{{ $order->items->count() - 3 }} produk lainnya</p>
                                        @endif
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-4 py-4 text-gray-500 whitespace-nowrap">
                                    {{ $order->order_date->format('d M Y') }}<br>
                                    <span class="text-xs text-gray-400">{{ $order->order_date->format('H:i') }}</span>
                                </td>

                                {{-- Total --}}
                                <td class="px-4 py-4 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ $order->formattedTotal() }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4">
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

                                {{-- Aksi --}}
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-xs whitespace-nowrap">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                                    Belum ada order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($orders->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
