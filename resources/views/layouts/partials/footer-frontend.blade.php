    <footer class="bg-slate-100 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Dira Jobs</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Membantumu menemukan peluang karir inklusif dan terbaik.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Navigasi Cepat</h3>
                    <ul class="text-sm space-y-1">
                        <li><a href="{{ route('home') }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">Beranda</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">Lowongan</a></li>
                        <li><a href="{{ route('articles.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">Artikel</a></li>
                        <li><a href="{{ route('workshops.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400">Workshop</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-2">Hubungi Kami</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Email: info@dirajobs.com</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Telepon: (024) 123-4567</p>
                    {{-- Tambahkan ikon media sosial jika ada --}}
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700 text-center text-sm text-slate-500 dark:text-slate-400">
                &copy; {{ date('Y') }} Dira Website. Dibuat dengan ❤️ oleh Dira.
            </div>
        </div>
    </footer>
    