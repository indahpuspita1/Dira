    @extends('layouts.frontend') {{-- Menggunakan layout frontend yang baru dibuat --}}

    @section('title', 'Selamat Datang di Dira - Portal Lowongan Kerja Inklusif')

    @section('content')
        {{-- HERO SECTION --}}
        <section class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 text-center">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-6">
                    Temukan Peluang Kerjamu Bersama <span class="block sm:inline">Dira</span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg sm:text-xl text-indigo-100 dark:text-purple-200 mb-10">
                    Portal lowongan kerja yang inklusif, menghubungkan talenta penyandang disabilitas dengan perusahaan yang peduli dan suportif.
                </p>
                <div class="space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('jobs.index') }}"
                       class="inline-block bg-white text-indigo-600 font-semibold px-8 py-3 rounded-lg shadow-lg hover:bg-indigo-50 text-lg transition-colors duration-150 ease-in-out transform hover:scale-105">
                        Cari Lowongan Sekarang
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-block bg-transparent border-2 border-white text-white font-semibold px-8 py-3 rounded-lg hover:bg-white hover:text-indigo-600 text-lg transition-colors duration-150 ease-in-out transform hover:scale-105">
                        Daftar Sebagai Pelamar
                    </a>
                </div>
            </div>
        </section>

        {{-- FITUR UNGGULAN SECTION --}}
        <section class="py-16 bg-slate-50 dark:bg-slate-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Kenapa Memilih Dira</h2>
                    <p class="mt-3 text-lg text-slate-600 dark:text-slate-400">Kami hadir untuk memberikan yang terbaik bagi Anda.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Fitur 1: Lowongan Inklusif --}}
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg text-center hover:shadow-indigo-500/30 transition-shadow duration-300">
                        {{-- Ganti dengan ikon yang sesuai --}}
                        <div class="flex items-center justify-center w-16 h-16 mx-auto bg-indigo-100 dark:bg-indigo-900 rounded-full mb-4 text-indigo-600 dark:text-indigo-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V7.875c0-.621.504-1.125 1.125-1.125H7.5m0-4.5h.008v.008H7.5v-.008Zm0 8.25h.008v.008H7.5v-.008Zm0 8.25h.008v.008H7.5v-.008Zm3.75-16.5h.008v.008H11.25v-.008Zm0 8.25h.008v.008H11.25v-.008Zm0 8.25h.008v.008H11.25v-.008Z" /></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Lowongan Inklusif</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Fokus pada lowongan yang ramah dan terbuka untuk penyandang disabilitas.</p>
                    </div>
                    {{-- Fitur 2: Artikel Bermanfaat --}}
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg text-center hover:shadow-green-500/30 transition-shadow duration-300">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto bg-green-100 dark:bg-green-900 rounded-full mb-4 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Artikel & Tips Karir</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Dapatkan wawasan, tips sukses wawancara, dan pengembangan diri.</p>
                    </div>
                    {{-- Fitur 3: Workshop Pengembangan --}}
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg text-center hover:shadow-yellow-500/30 transition-shadow duration-300">
                        <div class="flex items-center justify-center w-16 h-16 mx-auto bg-yellow-100 dark:bg-yellow-900 rounded-full mb-4 text-yellow-600 dark:text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" /></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2">Workshop Online</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm">Tingkatkan keahlianmu melalui berbagai pelatihan dan workshop interaktif.</p>
                    </div>
                    {{-- Tambahkan Fitur Konsultasi AI & Tes Bakat Minat jika sudah siap --}}
                </div>
            </div>
        </section>

        {{-- STATISTIK SINGKAT (OPSIONAL) --}}
        <section class="py-16 bg-indigo-50 dark:bg-indigo-900/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">120+</div>
                        <p class="mt-1 text-lg font-medium text-slate-700 dark:text-slate-300">Lowongan Aktif</p>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">50+</div>
                        <p class="mt-1 text-lg font-medium text-slate-700 dark:text-slate-300">Perusahaan Terdaftar</p>
                    </div>
                    <div>
                        <div class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400">300+</div>
                        <p class="mt-1 text-lg font-medium text-slate-700 dark:text-slate-300">Pelamar Terbantu</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- AJAKAN BERGABUNG SECTION --}}
        <section class="py-20 bg-slate-100 dark:bg-slate-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-4">Siap Memulai Perjalanan Karirmu?</h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-8">
                    Baik Anda seorang pencari kerja atau perusahaan yang mencari talenta, Dira adalah platform yang tepat untuk Anda.
                </p>
                <div class="space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('jobs.index') }}"
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3 rounded-lg shadow-lg text-lg transition-colors duration-150 ease-in-out transform hover:scale-105">
                        Saya Pelamar, Cari Kerja!
                    </a>
                    {{-- Ganti route ini jika ada halaman khusus untuk perusahaan --}}
                </div>
            </div>
        </section>

    @endsection
    