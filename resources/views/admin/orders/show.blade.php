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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info order + update status --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="space-y-1 text-sm">
                        <div class="text-gray-500">
                            Pelanggan: <span class="font-medium text-gray-800">{{ $order->user->name }}</span>
                            <span class="text-gray-400 ml-1">({{ $order->user->email }})</span>
                        </div>
                        <div class="text-gray-500">
                            Tanggal: <span class="font-medium text-gray-800">{{ $order->order_date->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div class="text-gray-500">
                            Total: <span class="font-bold text-gray-900 text-base">{{ $order->formattedTotal() }}</span>
                        </div>
                    </div>

                    {{-- Form update status --}}
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                          class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status"
                                class="border-gray-300 rounded-md text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach (['pending' => 'Menunggu', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $label)
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
                </div>
            </div>

            {{-- Item-item order --}}
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
                            <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">
                                Total
                            </td>
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
