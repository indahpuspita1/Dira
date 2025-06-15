    @extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}

    @section('header', 'Tambah Artikel Baru') {{-- Jika layoutmu menggunakan slot header --}}

    @section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Form Tambah Artikel</h1>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.articles._form', ['article' => null])
                {{-- article di-set null karena ini form create --}}
            </form>
        </div>
    </div>
    @endsection
    