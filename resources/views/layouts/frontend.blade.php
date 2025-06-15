    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Dira Jobs'))</title> {{-- Judul halaman dinamis --}}

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        {{-- Kamu bisa tambahkan font lain dari Google Fonts di sini jika mau --}}

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Tambahkan script untuk Alpine.js jika diperlukan untuk interaktivitas navbar mobile --}}
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        {{-- Favicon (Opsional, ganti dengan path faviconmu) --}}
        {{-- <link rel="icon" href="{{ asset('favicon.ico') }}"> --}}
    </head>
    <body class="font-figtree antialiased text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-900">
        <div class="min-h-screen flex flex-col">

            {{-- Memasukkan Navbar --}}
            @include('layouts.partials.navbar-frontend')

            <main class="flex-grow">
                @yield('content')
            </main>

            {{-- Memasukkan Footer --}}
            @include('layouts.partials.footer-frontend')

        </div>
        @stack('scripts') {{-- Untuk script tambahan per halaman --}}
    </body>
    </html>
    