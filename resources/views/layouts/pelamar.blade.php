{{-- resources/views/layouts/pelamar.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dira Website') }} - @yield('title', 'Selamat Datang')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col">
        {{-- TAMBAHKAN KELAS 'no-print' DI SINI --}}
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm sticky top-0 z-50 no-print">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <img src="{{ asset('images/logo-dira.png') }}" alt="Dira Admin Logo" class="block h-9 w-auto">
                        </div>
                        
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                @if(Auth::user()->role == 'pelamar')
                                    <a href="{{ route('pelamar.dashboard') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('pelamar.dashboard') ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                        Dashboard Saya
                                    </a>
                                @endif
                            @endauth
                            <a href="{{ route('jobs.index') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('jobs.index') || request()->routeIs('jobs.show') ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                Lowongan
                            </a>
                            <a href="{{ route('articles.index') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('articles.index') || request()->routeIs('articles.show') ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                Artikel
                            </a>
                            <a href="{{ route('workshops.index') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('workshops.index') || request()->routeIs('workshops.show') ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                Workshop
                            </a>
                            {{-- Link Chatbot AI --}}
                            <a href="{{ route('chat.groq.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('chat.groq.index') ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                Konsultasi AI
                            </a>
                            {{-- Link Tes Bakat Minat --}}
                            <a href="{{ route('quiz.start') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('quiz.start') || request()->routeIs('quiz.result') ? 'border-indigo-400 dark:border-indigo-600 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} text-sm font-medium leading-5 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out">
                                Tes Bakat Minat
                            </a>
                        </div>
                    </div>

                    {{-- Bagian Kanan Navbar (Login/Register atau User Info & Logout) --}}
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">Register</a>
                            @endif
                        @else
                            <div class="mr-3 relative">
                                <span class="text-sm font-medium leading-5 text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    Logout
                                </button>
                            </form>
                        @endguest
                    </div>
                    {{-- ... (Tombol hamburger untuk mobile) ... --}}
                </div>
            </div>
        </nav>

        {{-- Konten Halaman --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- Footer (Opsional) --}}
        {{-- TAMBAHKAN KELAS 'no-print' DI SINI --}}
        <footer class="bg-white dark:bg-slate-800 border-t no-print">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} Dira Website. Hak Cipta Dilindungi.
            </div>
        </footer>
    </div>
</body>
</html>