<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Sambutan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    Selamat datang, {{ auth()->user()->name }}!
                </h3>
                <p class="text-sm text-gray-500">Anda login sebagai <span class="font-medium text-green-600">Pelanggan</span>.</p>
            </div>

            {{-- Menu cepat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <a href="{{ route('pelanggan.products.index') }}"
                   class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition group">
                    <h4 class="font-semibold text-gray-800 mb-1 group-hover:text-indigo-600">Mulai Belanja</h4>
                    <p class="text-sm text-gray-500">Jelajahi semua produk yang tersedia.</p>
                </a>
                <a href="{{ route('pelanggan.orders.index') }}"
                   class="bg-white shadow-sm sm:rounded-lg p-6 hover:shadow-md transition group">
                    <h4 class="font-semibold text-gray-800 mb-1 group-hover:text-indigo-600">Riwayat Pesanan</h4>
                    <p class="text-sm text-gray-500">Lihat semua pesanan dan status pengirimannya.</p>
                </a>
            </div>

            {{-- Pesanan terbaru --}}

        </div>
    </div>
</x-app-layout>
