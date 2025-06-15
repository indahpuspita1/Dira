    @extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}

    @section('header', 'Detail Artikel')

    @section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
            <div class="mb-6">
                <a href="{{ route('admin.articles.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Daftar Artikel</a>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $article->title }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Ditulis oleh: {{ $article->admin->name ?? 'N/A' }} | Tanggal: {{ $article->created_at->isoFormat('D MMMM YYYY, HH:mm') }}
            </p>

            @if($article->image)
                <div class="mb-6">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="Gambar {{ $article->title }}" class="w-full max-h-[400px] object-contain rounded-lg shadow">
                </div>
            @endif

            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Isi Artikel:</h3>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('admin.articles.edit', $article->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Edit Artikel Ini
                </a>
            </div>
        </div>
    </div>
    @endsection
    