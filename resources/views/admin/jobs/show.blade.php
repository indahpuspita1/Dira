@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utamamu --}}

@section('header', 'Detail Lowongan Pekerjaan')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
        <div class="mb-6">
            <a href="{{ route('admin.jobs.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali ke Daftar Lowongan</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $job->title }}</h1>
        <p class="text-xl text-gray-700 dark:text-gray-300 mb-4">{{ $job->company }} - <span class="text-md text-gray-600 dark:text-gray-400">{{ $job->location }}</span></p>

        @if($job->image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $job->image) }}" alt="Gambar {{ $job->title }}" class="w-full max-h-96 object-contain rounded-lg shadow">
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">Deadline Pendaftaran:</h3>
                <p class="text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($job->deadline)->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">Kategori Disabilitas:</h3>
                @if($job->disabilityCategories->isNotEmpty())
                    <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                        @foreach($job->disabilityCategories as $category)
                            <li>{{ $category->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-400 italic">Tidak ada kategori disabilitas spesifik.</p>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Deskripsi Pekerjaan:</h3>
            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                {!! nl2br(e($job->description)) !!} {{-- nl2br untuk baris baru, e() untuk escaping --}}
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                Edit Lowongan Ini
            </a>
        </div>
    </div>
</div>
@endsection