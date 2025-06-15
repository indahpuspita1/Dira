@extends('layouts.pelamar') {{-- Pastikan ini layout frontend yang benar --}}

@section('title', 'Cari Lowongan Kerja')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900">

    {{-- HEADER DENGAN GRADASI --}}
    <div class="bg-gradient-to-b from-purple-100 to-blue-50 dark:from-purple-900/30 dark:to-slate-900 pt-16 pb-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-5xl">
                    Temukan Peluang Karir Impianmu
                </h1>
                <p class="mt-4 text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                    Jelajahi berbagai lowongan pekerjaan yang sesuai dengan keahlian dan minatmu. Dira hadir untuk menghubungkan talenta sepertimu dengan perusahaan yang tepat.
                </p>
            </div>

            <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm p-6 sm:p-8 rounded-xl shadow-lg mb-10 border border-slate-200/50 dark:border-slate-700/50">
                <form action="{{ route('jobs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2 lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cari Lowongan (Judul, Perusahaan, dll)</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Ketik kata kunci..."
                               class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-200 transition duration-150">
                    </div>
                    <div>
                        <label for="filter_disability_category" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kategori Disabilitas</label>
                        <select name="filter_disability_category" id="filter_disability_category"
                                class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-200 transition duration-150">
                            <option value="">Semua Kategori</option>
                            @foreach($disabilityCategories as $category)
                                <option value="{{ $category->name }}" {{ request('filter_disability_category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition duration-150 ease-in-out">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- DAFTAR LOWONGAN --}}
    <div class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            @if($jobs->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($jobs as $job)
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 ease-in-out flex flex-col">
                            <div class="relative">
                                <a href="{{ route('jobs.show', $job->id) }}" class="block h-48 bg-slate-200 dark:bg-slate-700">
                                    {{-- INI BAGIAN YANG PENTING UNTUK GAMBAR --}}
                                    @if($job->image)
                                        {{-- Memanggil URL langsung dari database --}}
                                        <img src="{{ $job->image }}" alt="Gambar untuk {{ $job->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        {{-- Fallback jika tidak ada gambar --}}
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-slate-500 dark:text-slate-400">Tidak Ada Gambar</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="absolute top-0 right-0 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 m-2 rounded-full">
                                    Deadline: {{ \Carbon\Carbon::parse($job->deadline)->isoFormat('D MMM YY') }}
                                </div>
                            </div>

                            <div class="p-6 flex-grow flex flex-col">
                                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-1 truncate" title="{{ $job->title }}">
                                    <a href="{{ route('jobs.show', $job->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $job->title }}</a>
                                </h2>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                    {{ $job->company }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                                    {{ $job->location }}
                                </p>

                                @if($job->disabilityCategories->isNotEmpty())
                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Disabilitas:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($job->disabilityCategories->take(3) as $category)
                                            <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full">{{ $category->name }}</span>
                                        @endforeach
                                        @if($job->disabilityCategories->count() > 3)
                                            <span class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 px-2 py-0.5 rounded-full">+{{ $job->disabilityCategories->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 flex-grow">
                                    {{ Str::limit(strip_tags($job->description), 100) }}
                                </p>

                                <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-700">
                                    <a href="{{ route('jobs.show', $job->id) }}"
                                       class="w-full block text-center bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 ease-in-out">
                                        Lihat Detail & Lamar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                <div class="mt-12">
                    {{ $jobs->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    {{-- Pesan jika lowongan tidak ditemukan --}}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
