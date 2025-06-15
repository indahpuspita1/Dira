@extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}
@section('header', 'Detail Workshop')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
        <div class="mb-6">
            <a href="{{ route('admin.workshops.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Kembali</a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $workshop->title }}</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
            Oleh: {{ $workshop->admin->name ?? 'N/A' }} | Dibuat: {{ $workshop->created_at->isoFormat('D MMMM YYYY') }}
        </p>

        @if($workshop->image)
            <div class="mb-6"><img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}" class="w-full max-h-[400px] object-contain rounded-lg shadow"></div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6">
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-200">Tanggal & Waktu:</h3>
                <p>{{ $workshop->date_time->isoFormat('dddd, D MMMM YYYY, HH:mm') }} WIB</p>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-gray-200">Lokasi/Link:</h3>
                <p class="break-all">
                    @if(filter_var($workshop->location_or_link, FILTER_VALIDATE_URL))
                        <a href="{{ $workshop->location_or_link }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $workshop->location_or_link }}</a>
                    @else
                        {{ $workshop->location_or_link }}
                    @endif
                </p>
            </div>
        </div>

        <div>
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-1">Deskripsi:</h3>
            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">{!! nl2br(e($workshop->description)) !!}</div>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('admin.workshops.edit', $workshop->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Edit Workshop</a>
        </div>
    </div>
</div>
@endsection