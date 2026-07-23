<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Nammerce') }} — Belanja Mudah & Terpercaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-gray-800">

{{-- ===== NAVBAR ===== --}}
<header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <svg class="w-8 h-8 text-indigo-600" viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                    <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z"/>
                </svg>
                <span class="text-xl font-bold text-indigo-600 tracking-tight">NAMMERCE</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-gray-600">
                <a href="#beranda" class="hover:text-indigo-600 transition">Beranda</a>
                <a href="#produk" class="hover:text-indigo-600 transition">Produk</a>
                <a href="#tentang" class="hover:text-indigo-600 transition">Tentang</a>
            </nav>

            {{-- Auth buttons --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
                        Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            Daftar
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open" class="md:hidden p-2 rounded-md text-gray-500 hover:bg-gray-100 transition">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-transition class="md:hidden border-t border-gray-100 py-3 space-y-1">
            <a href="#beranda" @click="open=false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-md">Beranda</a>
            <a href="#produk" @click="open=false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-md">Produk</a>
            <a href="#tentang" @click="open=false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-md">Tentang</a>
            <div class="pt-2 border-t border-gray-100 flex flex-col gap-2 px-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block text-center py-2 bg-indigo-600 text-white text-sm font-medium rounded-md">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block text-center py-2 border border-indigo-600 text-indigo-600 text-sm font-medium rounded-md">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block text-center py-2 bg-indigo-600 text-white text-sm font-medium rounded-md">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</header>

{{-- ===== HERO ===== --}}
<section id="beranda" class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-20 px-4">
    <div class="max-w-5xl mx-auto flex flex-col items-center text-center gap-6">
        <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full uppercase tracking-wider">
            Toko Online Terpercaya
        </span>
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight">
            Belanja Produk Berkualitas<br class="hidden sm:block">
            dengan <span class="text-indigo-600">Harga Terbaik</span>
        </h1>
        <p class="max-w-xl text-gray-500 text-lg leading-relaxed">
            Temukan ribuan produk pilihan dari berbagai kategori. Belanja mudah, aman, dan cepat hanya di {{ config('app.name', 'Nammerce') }}.
        </p>
        <div class="flex flex-col sm:flex-row gap-3">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="px-7 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-md">
                    Ke Dashboard
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="px-7 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-md">
                    Mulai Belanja
                </a>
                <a href="#produk"
                   class="px-7 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Lihat Produk
                </a>
            @endauth
        </div>

        {{-- Stats --}}
        <div class="mt-8 grid grid-cols-3 gap-8 w-full max-w-sm">
            <div class="text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ \App\Models\Product::count() }}+</p>
                <p class="text-xs text-gray-500 mt-0.5">Produk</p>
            </div>
            <div class="text-center border-x border-gray-200">
                <p class="text-2xl font-bold text-indigo-600">{{ \App\Models\Order::count() }}+</p>
                <p class="text-xs text-gray-500 mt-0.5">Order</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ \App\Models\User::where('role','pelanggan')->count() }}+</p>
                <p class="text-xs text-gray-500 mt-0.5">Pelanggan</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRODUK ===== --}}
<section id="produk" class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Produk Terbaru</h2>
            <p class="text-gray-500 mt-2">Produk pilihan yang siap untuk dipesan</p>
        </div>

        @if ($products->isEmpty())
            <p class="text-center text-gray-400 py-12">Belum ada produk yang tersedia saat ini.</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($products as $product)
                    <div class="bg-white border border-gray-100 rounded-xl overflow-hidden hover:shadow-lg transition group">
                        <div class="aspect-square bg-gray-50 overflow-hidden">
                            <img src="{{ $product->imageUrl() }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                        <div class="p-3">
                            <p class="text-xs text-indigo-500 font-medium mb-0.5">{{ $product->category->name }}</p>
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug">
                                {{ $product->name }}
                            </h3>
                            <p class="mt-1.5 text-sm font-bold text-gray-900">{{ $product->formattedPrice() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="inline-block px-7 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Lihat Semua Produk
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="inline-block px-7 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                        Daftar untuk Belanja
                    </a>
                @endauth
            </div>
        @endif
    </div>
</section>

{{-- ===== KEUNGGULAN ===== --}}
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Mengapa Memilih Kami?</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Terpercaya & Aman</h3>
                <p class="text-sm text-gray-500">Transaksi aman dengan sistem keamanan berlapis.</p>
            </div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Harga Terbaik</h3>
                <p class="text-sm text-gray-500">Produk berkualitas dengan harga yang kompetitif.</p>
            </div>
            <div class="bg-white rounded-xl p-6 text-center shadow-sm">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-1">Proses Cepat</h3>
                <p class="text-sm text-gray-500">Checkout mudah dan pemrosesan order yang cepat.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== TENTANG ===== --}}
<section id="tentang" class="py-16 px-4 bg-white">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Tentang Nammerce</h2>
        <p class="text-gray-500 leading-relaxed mb-6">
            Nammerce adalah platform e-commerce yang hadir untuk mempermudah pengalaman belanja online Anda.
            Kami menyediakan berbagai produk berkualitas dari berbagai kategori dengan harga yang transparan
            dan proses belanja yang mudah.
        </p>
        <p class="text-gray-500 leading-relaxed">
            Dengan sistem manajemen order yang terintegrasi, admin dapat memantau dan memproses setiap pesanan
            secara real-time, sementara pelanggan dapat menikmati pengalaman belanja yang nyaman dan aman.
        </p>
    </div>
</section>

{{-- ===== CTA ===== --}}
@guest
<section class="py-16 px-4 bg-indigo-600">
    <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-white mb-3">Siap Mulai Belanja?</h2>
        <p class="text-indigo-200 mb-8">Daftar sekarang dan nikmati kemudahan berbelanja.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="px-8 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50 transition shadow">
                Daftar Sekarang
            </a>
            <a href="{{ route('login') }}"
               class="px-8 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                Login
            </a>
        </div>
    </div>
</section>
@endguest

{{-- ===== FOOTER ===== --}}
<footer class="bg-gray-900 text-gray-400 py-8 px-4">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-3 text-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                <path d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125Z"/>
            </svg>
            <span class="text-white font-semibold">Nammerce</span>
        </div>
        <p>&copy; {{ date('Y') }} Nammerce. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
