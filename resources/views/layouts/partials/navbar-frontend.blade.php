    <nav x-data="{ open: false }" class="bg-white dark:bg-slate-800 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}">
                            {{-- Ganti dengan logo Dira-mu, bisa berupa teks atau gambar --}}
                             <img src="{{ asset('images/logo-dira.png') }}" alt="Dira Jobs Logo" class="block h-9 w-auto">
                            {{-- Contoh jika pakai logo gambar: --}}
                            {{-- <img src="{{ asset('images/logo-dira.png') }}" alt="Dira Jobs Logo" class="block h-9 w-auto"> --}}
                        </a>
                    </div>

                    <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                        <x-frontend-nav-link :href="route('home')" :active="request()->routeIs('home')">Beranda</x-frontend-nav-link>
                        <x-frontend-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.index') || request()->routeIs('jobs.show')">Lowongan</x-frontend-nav-link>
                        <x-frontend-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.index') || request()->routeIs('articles.show')">Artikel</x-frontend-nav-link>
                        <x-frontend-nav-link :href="route('workshops.index')" :active="request()->routeIs('workshops.index') || request()->routeIs('workshops.show')">Workshop</x-frontend-nav-link>
                        {{-- Tampilkan jika fitur sudah siap --}}
                        @if(Route::has('chat.groq.index'))
                            <x-frontend-nav-link :href="route('chat.groq.index')" :active="request()->routeIs('chat.groq.index')">Konsultasi AI</x-frontend-nav-link>
                        @endif
                        @if(Route::has('quiz.start'))
                            <x-frontend-nav-link :href="route('quiz.start')" :active="request()->routeIs('quiz.start') || request()->routeIs('quiz.result')">Tes Bakat Minat</x-frontend-nav-link>
                        @endif
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    @guest
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-150 ease-in-out">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                                Daftar
                            </a>
                        @endif
                    @else
                        {{-- Dropdown untuk User yang Sudah Login --}}
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                            {{-- Dropdown Content --}}
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                                 style="display: none;"
                                 @click="open = false">
                                <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white dark:bg-slate-700">
                                    @if(Auth::user()->role == 'admin')
                                        <x-dropdown-link :href="route('admin.dashboard')">Dashboard Admin</x-dropdown-link>
                                    @elseif(Auth::user()->role == 'pelamar')
                                        <x-dropdown-link :href="route('pelamar.dashboard')">Dashboard Saya</x-dropdown-link>
                                    @endif
                                    <x-dropdown-link :href="route('profile.edit')">Profil Saya</x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>

                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 dark:text-slate-500 hover:text-slate-500 dark:hover:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 focus:outline-none focus:bg-slate-100 dark:focus:bg-slate-900 focus:text-slate-500 dark:focus:text-slate-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">Beranda</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('jobs.index')" :active="request()->routeIs('jobs.index') || request()->routeIs('jobs.show')">Lowongan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('articles.index')" :active="request()->routeIs('articles.index') || request()->routeIs('articles.show')">Artikel</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('workshops.index')" :active="request()->routeIs('workshops.index') || request()->routeIs('workshops.show')">Workshop</x-responsive-nav-link>
                @if(Route::has('chat.groq.index'))
                    <x-responsive-nav-link :href="route('chat.groq.index')" :active="request()->routeIs('chat.groq.index')">Konsultasi AI</x-responsive-nav-link>
                @endif
                @if(Route::has('quiz.start'))
                    <x-responsive-nav-link :href="route('quiz.start')" :active="request()->routeIs('quiz.start') || request()->routeIs('quiz.result')">Tes Bakat Minat</x-responsive-nav-link>
                @endif
            </div>

            <div class="pt-4 pb-1 border-t border-slate-200 dark:border-slate-600">
                @auth
                    <div class="px-4">
                        <div class="font-medium text-base text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                         @if(Auth::user()->role == 'admin')
                            <x-responsive-nav-link :href="route('admin.dashboard')">Dashboard Admin</x-responsive-nav-link>
                        @elseif(Auth::user()->role == 'pelamar')
                            <x-responsive-nav-link :href="route('pelamar.dashboard')">Dashboard Saya</x-responsive-nav-link>
                        @endif
                        <x-responsive-nav-link :href="route('profile.edit')">Profil Saya</x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                @else
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
                        @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">Daftar</x-responsive-nav-link>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </nav>
    