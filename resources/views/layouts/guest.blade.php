<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dira Jobs') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- KONTENER UTAMA SEKARANG LANGSUNG GRID 2 KOLOM YANG MENGISI LAYAR --}}
        <div class="min-h-screen md:grid md:grid-cols-2">

            {{-- KOLOM KIRI: FORM LOGIN --}}
            <div class="flex flex-col justify-center items-center px-6 py-12 lg:px-8 bg-white dark:bg-gray-800">
                <div class="w-full sm:max-w-md">
                    {{-- Bagian Logo Dira --}}
                    <div class="mb-8 text-center">
                        <a href="/" class="inline-block">
                            {{-- MENGGANTI LOGO LARAVEL DENGAN LOGO DIRA --}}
                            <img src="{{ asset('images/logo-dira.png') }}" alt="Logo Dira Jobs" class="mx-auto w-auto h-20">
                        </a>
                    </div>

                    {{-- $slot adalah tempat di mana konten dari login.blade.php atau register.blade.php akan dimasukkan --}}
                    {{ $slot }}
                </div>
            </div>

            {{-- KOLOM KANAN: GAMBAR --}}
            <div class="hidden md:block relative">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=2070&auto=format&fit=crop');">
                     {{-- Overlay Gradasi (opsional) --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-indigo-700/50 via-purple-700/30 to-transparent"></div>
                </div>
                 {{-- Teks di atas gambar (opsional) --}}
                <div class="relative h-full flex flex-col justify-end p-12">
                    <h2 class="text-white text-3xl lg:text-4xl font-bold leading-tight">Membuka Pintu,<br>Membangun Kesempatan.</h2>
                    <p class="text-white/80 mt-2 max-w-sm">
                        Setiap individu berhak mendapatkan kesempatan yang setara untuk berkarya dan meraih impian.
                    </p>
                </div>
            </div>

        </div>
    </body>
</html>
