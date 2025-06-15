@extends('layouts.pelamar')


@section('title', 'Artikel & Berita Terbaru')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul Halaman --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-5xl">
                Wawasan & Informasi Terkini
            </h1>
            <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                Temukan artikel menarik, tips karir, dan berita terbaru seputar dunia kerja dan disabilitas.
            </p>
        </div>

        {{-- Form Pencarian Artikel --}}
        <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-xl shadow-lg mb-10 max-w-2xl mx-auto">
            <form action="{{ route('articles.index') }}" method="GET" class="flex items-center gap-4">
                <div class="flex-grow">
                    <label for="search" class="sr-only">Cari Artikel</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Ketik judul atau isi artikel..."
                           class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-slate-200 transition duration-150">
                </div>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition duration-150 ease-in-out">
                    Cari
                </button>
            </form>
        </div>

        {{-- Daftar Artikel --}}
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($articles as $article)
                    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 ease-in-out flex flex-col">
                        <a href="{{ route('articles.show', $article->id) }}" class="block">
                            @if($article->image)
                                <img src="{{ $article->image }}" alt="{{ $article->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                    <span class="text-slate-500 dark:text-slate-400 text-lg">Gambar Tidak Tersedia</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-6 flex-grow flex flex-col">
                            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2">
                                <a href="{{ route('articles.show', $article->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-150" title="{{ $article->title }}">
                                    {{ Str::limit($article->title, 60) }}
                                </a>
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">
                                Oleh: {{ $article->admin->name ?? 'Tim Dira' }} | {{ $article->created_at->isoFormat('D MMM YYYY') }}
                            </p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4 flex-grow">
                                {{ Str::limit(strip_tags($article->content), 120) }}
                            </p>
                            <div class="mt-auto pt-4 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('articles.show', $article->id) }}"
                                   class="inline-flex items-center text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors duration-150">
                                    Baca Selengkapnya
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
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-12">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-400 dark:text-slate-500 mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 18V7.875c0-.621.504-1.125 1.125-1.125H7.5" />
                </svg>
                <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-300 mb-2">Belum Ada Artikel</h3>
                <p class="text-slate-500 dark:text-slate-400">
                    Saat ini belum ada artikel yang dipublikasikan. Silakan periksa kembali nanti.
                </p>
                @if(request()->has('search'))
                <a href="{{ route('articles.index') }}" class="mt-4 inline-block text-indigo-600 dark:text-indigo-400 hover:underline">
                    Hapus Filter & Lihat Semua Artikel
                </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
