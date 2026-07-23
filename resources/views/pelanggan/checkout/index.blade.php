<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pelanggan.cart.index') }}" class="text-gray-400 hover:text-gray-600">&larr;</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Checkout</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ route('pelanggan.checkout.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                    {{-- ── Kiri: Form pengiriman & pembayaran ── --}}
                    <div class="lg:col-span-3 space-y-6">

                        {{-- Informasi Pengiriman --}}
                        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                            <h3 class="font-semibold text-gray-800">Informasi Pengiriman</h3>

                            {{-- Nama penerima --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Penerima <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="shipping_name"
                                       value="{{ old('shipping_name', auth()->user()->name) }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('shipping_name') border-red-400 @enderror"
                                       placeholder="Nama lengkap penerima">
                                @error('shipping_name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor telepon --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="shipping_phone"
                                       value="{{ old('shipping_phone') }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('shipping_phone') border-red-400 @enderror"
                                       placeholder="08xxxxxxxxxx">
                                @error('shipping_phone')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat pengiriman --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat Pengiriman <span class="text-red-500">*</span>
                                </label>
                                <textarea name="shipping_address" rows="3"
                                          class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('shipping_address') border-red-400 @enderror"
                                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
                            <h3 class="font-semibold text-gray-800">Metode Pembayaran</h3>
                            @error('payment_method')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @enderror

                            @foreach ([
                                'transfer'  => ['label' => 'Transfer Bank', 'desc' => 'BCA / Mandiri / BNI / BRI', 'icon' => '🏦'],
                                'ewallet'   => ['label' => 'E-Wallet', 'desc' => 'GoPay / OVO / DANA / ShopeePay', 'icon' => '📱'],
                                'cod'       => ['label' => 'Bayar di Tempat (COD)', 'desc' => 'Bayar saat paket tiba', 'icon' => '🚚'],
                            ] as $value => $opt)
                                <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer transition
                                              {{ old('payment_method') === $value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}"
                                       x-data
                                       :class="$refs.pm_{{ $value }}.checked ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300'">
                                    <input type="radio" name="payment_method" value="{{ $value }}"
                                           x-ref="pm_{{ $value }}"
                                           {{ old('payment_method') === $value ? 'checked' : '' }}
                                           class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ $opt['icon'] }} {{ $opt['label'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $opt['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                    </div>

                    {{-- ── Kanan: Ringkasan pesanan ── --}}
                    <div class="lg:col-span-2 space-y-4">

                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden sticky top-4">
                            <div class="px-5 py-4 border-b border-gray-100">
                                <h3 class="font-semibold text-gray-800">Ringkasan Pesanan</h3>
                            </div>

                            {{-- Item list --}}
                            <div class="px-5 py-4 space-y-3 max-h-72 overflow-y-auto">
                                @foreach ($items as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item['image'] ? asset('storage/'.$item['image']) : asset('images/no-image.png') }}"
                                             alt="{{ $item['name'] }}"
                                             class="w-12 h-12 object-cover rounded-md border border-gray-100 flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                                            <p class="text-xs text-gray-400">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['quantity'] }}
                                            </p>
                                        </div>
                                        <p class="text-xs font-semibold text-gray-800 whitespace-nowrap">
                                            Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total --}}
                            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-700">Total</span>
                                    <span class="text-lg font-bold text-gray-900">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Tombol pesan --}}
                            <div class="px-5 pb-5">
                                <button type="submit"
                                        class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition mt-4">
                                    Konfirmasi &amp; Pesan
                                </button>
                                <a href="{{ route('pelanggan.cart.index') }}"
                                   class="block text-center mt-2 text-sm text-gray-500 hover:text-gray-700">
                                    &larr; Kembali ke Keranjang
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
