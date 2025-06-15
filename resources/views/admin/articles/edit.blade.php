    @extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}

    @section('header', 'Edit Artikel')

    @section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Form Edit Artikel: {{ $article->title }}</h1>

            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Method spoofing untuk update --}}
                @include('admin.articles._form', ['article' => $article])
            </form>
        </div>
    </div>
    @endsection
    