<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        Selamat datang, {{ auth()->user()->name }}!
                    </h3>
                    <p class="text-sm text-gray-500">Anda login sebagai <span class="font-medium text-green-600">Pelanggan</span>.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h4 class="font-semibold text-gray-800 mb-2">Mulai Belanja</h4>
                <p class="text-sm text-gray-500">Jelajahi produk dan buat pesanan baru.</p>
                {{-- Route::get('/products', ...) akan ditambahkan nanti --}}
            </div>

        </div>
    </div>
</x-app-layout>
