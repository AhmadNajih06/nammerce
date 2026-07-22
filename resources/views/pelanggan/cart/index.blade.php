<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Keranjang Belanja</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

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

            @if (empty($items))
                <div class="bg-white shadow-sm sm:rounded-lg p-12 text-center">
                    <p class="text-gray-400 mb-3">Keranjang Anda kosong.</p>
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
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-4 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($items as $id => $item)
                                <tr class="hover:bg-gray-50">
                                    {{-- Produk --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/no-image.png') }}"
                                                 alt="{{ $item['name'] }}"
                                                 class="w-12 h-12 object-cover rounded border border-gray-200 flex-shrink-0">
                                            <span class="font-medium text-gray-800">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    {{-- Harga --}}
                                    <td class="px-4 py-3 text-gray-600">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    {{-- Jumlah --}}
                                    <td class="px-4 py-3">
                                        <form action="{{ route('pelanggan.cart.update', $id) }}"
                                              method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity"
                                                   value="{{ $item['quantity'] }}"
                                                   min="1" max="{{ $item['stock'] }}"
                                                   class="w-16 border-gray-300 rounded-md text-sm text-center">
                                            <button type="submit"
                                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                    {{-- Subtotal --}}
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </td>
                                    {{-- Hapus --}}
                                    <td class="px-4 py-3 text-right">
                                        <form action="{{ route('pelanggan.cart.remove', $id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 text-xs font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer: kosongkan & total + checkout --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <form action="{{ route('pelanggan.cart.clear') }}" method="POST"
                          onsubmit="return confirm('Kosongkan semua keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">
                            Kosongkan Keranjang
                        </button>
                    </form>

                    <div class="bg-white shadow-sm sm:rounded-lg p-4 w-full sm:w-72 space-y-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total</span>
                            <span class="font-bold text-gray-900 text-base">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('pelanggan.checkout.index') }}"
                           class="block w-full text-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition">
                            Checkout &rarr;
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
