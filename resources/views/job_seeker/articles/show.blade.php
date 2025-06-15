@extends('layouts.pelamar') {{-- GANTI DENGAN LAYOUT PUBLIK/FRONTEND KAMU --}}

@section('title', $article->title)

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            {{-- Tombol Kembali --}}
            <div class="mb-8">
                <a href="{{ route('articles.index') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors duration-150 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                        <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Daftar Artikel
                </a>
            </div>

            <article class="bg-white dark:bg-slate-800 shadow-xl rounded-xl overflow-hidden">
                @if($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-auto max-h-[500px] object-cover">
                @endif

                <div class="p-6 sm:p-8 md:p-10">
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-100 mb-3 leading-tight">{{ $article->title }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                        Ditulis oleh: <span class="font-medium">{{ $article->admin->name ?? 'Tim Dira' }}</span>
                        <span class="mx-2">|</span>
                        Dipublikasikan pada: <span class="font-medium">{{ $article->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                    </p>

                    <div class="prose prose-lg prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed">
                        {!! nl2br(e($article->content)) !!} {{-- nl2br untuk baris baru, e() untuk escaping HTML --}}
                    </div>
                </div>
            </article>

            {{-- Bagian Komentar (Opsional, jika ingin ada fitur komentar) --}}
            {{-- <div class="mt-12 bg-white dark:bg-slate-800 shadow-xl rounded-xl p-6 sm:p-8">
                <h3 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Komentar</h3>
                </div> --}}

        </div>
    </div>
</div>
@endsection
