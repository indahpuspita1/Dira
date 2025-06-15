@extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}
@section('header', 'Edit Workshop')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Form Edit Workshop: {{ $workshop->title }}</h1>
        <form action="{{ route('admin.workshops.update', $workshop->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.workshops._form', ['workshop' => $workshop])
        </form>
    </div>
</div>
@endsection