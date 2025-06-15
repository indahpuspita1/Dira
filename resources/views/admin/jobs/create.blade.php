@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utamamu --}}

@section('header', 'Tambah Lowongan Pekerjaan Baru')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Form Tambah Lowongan Pekerjaan</h1>

        <form action="{{ route('admin.jobs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.jobs._form', ['job' => null, 'disabilityCategories' => $disabilityCategories ?? collect()])
            {{-- disabilityCategories dikirim dari JobController@create --}}
            {{-- job di-set null karena ini form create --}}
        </form>
    </div>
</div>
@endsection