@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utamamu --}}

@section('header', 'Edit Lowongan Pekerjaan')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Form Edit Lowongan: {{ $job->title }}</h1>

        <form action="{{ route('admin.jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Method spoofing untuk update --}}
            @include('admin.jobs._form', ['job' => $job, 'disabilityCategories' => $disabilityCategories ?? collect()])
            {{-- job dan disabilityCategories dikirim dari JobController@edit --}}
        </form>
    </div>
</div>
@endsection