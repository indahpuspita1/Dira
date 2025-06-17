@extends('layouts.pelamar')


@section('title', 'Workshop & Pelatihan')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul Halaman --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-5xl">
                Tingkatkan Keahlianmu Bersama Kami
            </h1>
            <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Ikuti berbagai workshop dan pelatihan yang dirancang untuk membantumu berkembang dan siap menghadapi tantangan karir.
            </p>
        </div>

        {{-- Form Pencarian Workshop (Opsional, bisa ditambahkan seperti di Artikel) --}}
        {{--
        <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-xl shadow-lg mb-10 max-w-2xl mx-auto">
            <form action="{{ route('workshops.index') }}" method="GET" class="flex items-center gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari workshop..." class="flex-grow ...">
                <button type="submit" class="bg-indigo-600 ...">Cari</button>
            </form>
        </div>
        --}}

        {{-- Daftar Workshop --}}
        @if($workshops->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($workshops as $workshop)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 ease-in-out flex flex-col">
                        <a href="{{ route('workshops.show', $workshop->id) }}" class="block">
                            @if($workshop->image)
                                <img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                    <span class="text-slate-500 dark:text-slate-400 text-lg">Gambar Tidak Tersedia</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-6 flex-grow flex flex-col">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                <a href="{{ route('workshops.show', $workshop->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-150" title="{{ $workshop->title }}">
                                    {{ Str::limit($workshop->title, 50) }}
                                </a>
                            </h2>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline-block mr-1">
                                  <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c0-.414.336-.75.75-.75h10.5a.75.75 0 01.75.75v1.25a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75V7.5z" clip-rule="evenodd" />
                                </svg>
                                {{ $workshop->date_time->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB
                            </p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline-block mr-1">
                                  <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.145l.002-.001L10.308 19 10 19s-.11.02-.308-.066Zm1.23-1.045a4.25 4.25 0 0 0-2.634 0L6.087 15.75l.001-.001a4.25 4.25 0 0 0-2.43-2.431L1.522 10.734a.75.75 0 0 0 0-1.468l2.134-2.537L6.087 4.25l-.001.001a4.25 4.25 0 0 0 2.43-2.43L10.082 1.32a.75.75 0 0 0 1.468 0l2.537 2.134 2.431 2.43.001.001a4.25 4.25 0 0 0 2.43 2.431l2.134 2.537a.75.75 0 0 0 0 1.468l-2.134 2.537-2.431 2.43-.001-.001a4.25 4.25 0 0 0-2.43 2.431l-2.537 2.134ZM10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" clip-rule="evenodd" />
                                </svg>
                                @if(filter_var($workshop->location_or_link, FILTER_VALIDATE_URL))
                                    <a href="{{ $workshop->location_or_link }}" target="_blank" class="hover:underline">Link Online</a>
                                @else
                                    {{ $workshop->location_or_link }}
                                @endif
                            </p>
                            <p class="text-sm font-semibold {{ $workshop->price > 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }} mb-3">
                                Harga: {{ $workshop->price > 0 ? 'Rp ' . number_format($workshop->price, 0, ',', '.') : 'Gratis' }}
                            </p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($workshop->description), 100) }}
                            </p>
                            <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('workshops.show', $workshop->id) }}"
                                   class="inline-flex items-center text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors duration-150">
                                    Lihat Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                                      <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            <div class="mt-12">
                {{ $workshops->links() }}
            </div>
        @else
            <div class="text-center py-12">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-400 dark:text-slate-500 mx-auto mb-4">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0021 18V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v12a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">Belum Ada Workshop</h3>
                <p class="text-slate-500 dark:text-slate-400">
                    Saat ini belum ada workshop atau pelatihan yang dijadwalkan. Silakan periksa kembali nanti.
                </p>
                {{-- @if(request()->has('search')) ... @endif --}}
            </div>
        @endif
    </div>
</div>
@endsection