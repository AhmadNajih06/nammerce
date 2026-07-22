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
            <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-2 flex-wrap">
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
            </form>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider"># Order</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 font-medium text-indigo-600">
                                    <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a>
                                </td>
                                <td class="px-5 py-3 text-gray-700">
                                    <div>{{ $order->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $order->user->email }}</div>
                                </td>
                                <td class="px-5 py-3 text-gray-500">
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
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-medium text-xs">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400">
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
