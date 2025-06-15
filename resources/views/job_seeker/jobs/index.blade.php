@extends('layouts.pelamar')


@section('title', 'Cari Lowongan Kerja') {{-- Judul Halaman Browser --}}

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul Halaman dan Deskripsi Singkat --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-5xl">
                Temukan Peluang Karir Impianmu
            </h1>
            <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Jelajahi berbagai lowongan pekerjaan yang sesuai dengan keahlian dan minatmu. Dira hadir untuk menghubungkan talenta sepertimu dengan perusahaan yang tepat.
            </p>
        </div>

        {{-- Form Pencarian dan Filter --}}
        <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-xl shadow-lg mb-10">
            <form action="{{ route('jobs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                {{-- Input Pencarian Umum --}}
                <div class="md:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Cari Lowongan (Judul, Perusahaan, Lokasi, Kategori)</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Ketik kata kunci..."
                           class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-200 transition duration-150">
                </div>

                {{-- Filter Kategori Disabilitas --}}
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

                {{-- Tombol Submit Filter --}}
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition duration-150 ease-in-out">
                        Cari
                    </button>
                </div>
            </form>
        </div>


        {{-- Daftar Lowongan Pekerjaan --}}
        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($jobs as $job)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 ease-in-out flex flex-col">
                        <div class="relative">
                            @if($job->image)
                                <img src="{{ $job->image }}" alt="Gambar {{ $job->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                    <span class="text-slate-500 dark:text-slate-400 text-lg">Tidak Ada Gambar</span>
                                </div>
                            @endif
                            <div class="absolute top-0 right-0 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 m-2 rounded-full">
                                Deadline: {{ \Carbon\Carbon::parse($job->deadline)->isoFormat('D MMM YY') }}
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-1 truncate" title="{{ $job->title }}">
                                {{ $job->title }}
                            </h2>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline-block mr-1 text-indigo-500">
                                    <path fill-rule="evenodd" d="M1 10.372a.75.75 0 0 1 .55-.722L2.63 9.25l.153-.076A2.5 2.5 0 0 1 5.25 7.5h2.576a.75.75 0 0 1 .738.52l.243.973.244.973a.75.75 0 0 1-.33 1.016l-.99.66a.75.75 0 0 1-1.042-.23L6.25 10l-1.72 1.72a.75.75 0 0 1-1.06 0L1.75 10l-.99-.99a.75.75 0 0 1 .24-1.042l.99-.66A.75.75 0 0 1 1 10.372ZM12.25 7.5a.75.75 0 0 0-.738.52l-.243.973-.244.973a.75.75 0 0 0 .33 1.016l.99.66a.75.75 0 0 0 1.042-.23L13.75 10l1.72 1.72a.75.75 0 0 0 1.06 0L18.25 10l.99-.99a.75.75 0 0 0-.24-1.042l-.99-.66a.75.75 0 0 0-1.042.23L16.75 10l-1.72-1.72a.75.75 0 0 0-1.06 0L12.25 7.5Z" clip-rule="evenodd" />
                                    <path d="M9.245 12.832a.75.75 0 0 0 .738.52h2.576a2.5 2.5 0 0 0 2.468-1.674l.153-.076.097-.048a.75.75 0 0 0 .55-.722V8.628a.75.75 0 0 0-1.24-.55l-.99.99a.75.75 0 0 0 0 1.06L13.75 11l-1.72-1.72a.75.75 0 0 0-1.06 0L9.25 11l-.99.99a.75.75 0 0 0 .24 1.042l.99.66A.75.75 0 0 0 9.245 12.832Z" />
                                </svg>
                                {{ $job->company }}
                            </p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline-block mr-1 text-indigo-500">
                                  <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.145l.002-.001L10.308 19 10 19s-.11.02-.308-.066Zm1.23-1.045a4.25 4.25 0 0 0-2.634 0L6.087 15.75l.001-.001a4.25 4.25 0 0 0-2.43-2.431L1.522 10.734a.75.75 0 0 0 0-1.468l2.134-2.537L6.087 4.25l-.001.001a4.25 4.25 0 0 0 2.43-2.43L10.082 1.32a.75.75 0 0 0 1.468 0l2.537 2.134 2.431 2.43.001.001a4.25 4.25 0 0 0 2.43 2.431l2.134 2.537a.75.75 0 0 0 0 1.468l-2.134 2.537-2.431 2.43-.001-.001a4.25 4.25 0 0 0-2.43 2.431l-2.537 2.134ZM10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" clip-rule="evenodd" />
                                </svg>
                                {{ $job->location }}
                            </p>

                            @if($job->disabilityCategories->isNotEmpty())
                            <div class="mb-3">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kategori Disabilitas:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($job->disabilityCategories->take(3) as $category) {{-- Tampilkan maks 3 kategori --}}
                                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full">{{ $category->name }}</span>
                                    @endforeach
                                    @if($job->disabilityCategories->count() > 3)
                                        <span class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 px-2 py-0.5 rounded-full">+{{ $job->disabilityCategories->count() - 3 }} lainnya</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($job->description), 100) }} {{-- Hapus tag HTML dan batasi deskripsi --}}
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-400 dark:text-slate-500 mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">Oops! Lowongan Tidak Ditemukan</h3>
                <p class="text-slate-500 dark:text-slate-400">
                    Saat ini tidak ada lowongan yang sesuai dengan pencarianmu. Coba kata kunci lain atau periksa kembali nanti.
                </p>
                @if(request()->has('search') || request()->has('filter_disability_category') || request()->has('filter_location'))
                <a href="{{ route('jobs.index') }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                    Hapus Filter & Lihat Semua Lowongan
                </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
