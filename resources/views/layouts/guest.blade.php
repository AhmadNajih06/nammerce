<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Nammerce') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- Panel kiri — branding --}}
    <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-[#1a4f8a] to-[#0f3460] p-12 text-white">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <x-application-logo class="h-16 w-auto" />
        </a>

        {{-- Tagline tengah --}}
        <div class="space-y-5">
            <h2 class="text-4xl font-bold leading-tight">
                Belanja Mudah,<br>Harga Terbaik.
            </h2>
            <p class="text-blue-200 text-base leading-relaxed max-w-sm">
                Temukan ribuan produk bangunan berkualitas dan nikmati pengalaman belanja online yang menyenangkan.
            </p>

            {{-- Feature list --}}
            <ul class="space-y-3 pt-2">
                @foreach ([
                    'Produk bangunan berkualitas pilihan',
                    'Harga transparan tanpa biaya tersembunyi',
                    'Lacak pesanan secara real-time',
                ] as $feat)
                    <li class="flex items-center gap-3 text-sm text-blue-100">
                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        {{ $feat }}
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Footer kiri --}}
        <p class="text-blue-300 text-sm">&copy; {{ date('Y') }} Nammerce — Toko Bangunan Online</p>
    </div>

    {{-- Panel kanan — form --}}
    <div class="flex flex-col justify-center items-center px-6 py-12 bg-white">

        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8 flex flex-col items-center gap-2">
            <a href="{{ url('/') }}">
                <x-application-logo class="h-16 w-auto" />
            </a>
        </div>

        {{-- Form card --}}
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>

    </div>
</div>

</body>
</html>
