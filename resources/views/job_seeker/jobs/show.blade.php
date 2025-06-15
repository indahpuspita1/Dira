@extends('layouts.pelamar') {{-- Pastikan ini adalah layout publik/pelamar yang benar --}}

@section('title', $job->title . ' - Lowongan Kerja')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Tombol Kembali & Notifikasi --}}
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                        <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Daftar Lowongan
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-md shadow-md" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 rounded-md shadow-md" role="alert">
                    <p class="font-bold">Oops!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-6 bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-300 p-4 rounded-md shadow-md" role="alert">
                    <p class="font-bold">Perhatian!</p>
                    <p>{{ session('warning') }}</p>
                </div>
            @endif

            <article class="bg-white dark:bg-slate-800 shadow-xl rounded-xl overflow-hidden">
                {{-- Header Lowongan dengan Gambar --}}
                <div class="relative">
                    @if($job->image)
                        <img src="{{ asset('storage/' . $job->image) }}" alt="Gambar {{ $job->title }}" class="w-full h-64 sm:h-80 md:h-96 object-cover">
                    @else
                        <div class="w-full h-64 sm:h-80 md:h-96 bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                            <span class="text-slate-500 dark:text-slate-400 text-2xl">Gambar Tidak Tersedia</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 sm:p-8">
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-1 leading-tight">{{ $job->title }}</h1>
                        <p class="text-lg sm:text-xl text-indigo-300 font-medium">{{ $job->company }}</p>
                    </div>
                </div>

                <div class="p-6 sm:p-8">
                    {{-- Informasi Utama: Lokasi & Deadline --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Lokasi</h3>
                            <p class="text-lg text-slate-800 dark:text-slate-200">{{ $job->location }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Deadline Pendaftaran</h3>
                            <p class="text-lg text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($job->deadline)->isoFormat('dddd, D MMMM YYYY') }}</p>
                            @if(\Carbon\Carbon::parse($job->deadline)->isPast())
                                <span class="text-xs text-red-500 dark:text-red-400">(Sudah Kedaluwarsa)</span>
                            @else
                                <span class="text-xs text-green-600 dark:text-green-400"> (Sisa: {{ \Carbon\Carbon::parse($job->deadline)->diffForHumans(null, true, false, 2) }})</span>
                            @endif
                        </div>
                    </div>

                    {{-- Kategori Disabilitas --}}
                    @if($job->disabilityCategories->isNotEmpty())
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Kategori Disabilitas yang Didukung</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($job->disabilityCategories as $category)
                                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 text-sm font-medium px-3 py-1 rounded-full">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Deskripsi Pekerjaan --}}
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-3">Deskripsi Pekerjaan</h3>
                        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    {{-- Tombol Lamar Sekarang (SUDAH DIPERBARUI) --}}
                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
                        @if(\Carbon\Carbon::parse($job->deadline)->isFuture() || \Carbon\Carbon::parse($job->deadline)->isToday())
                            @guest {{-- Jika user belum login --}}
                                <a href="{{ route('login', ['redirect' => route('jobs.apply.form', $job->id) ]) }}" class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out">
                                    Login untuk Melamar
                                </a>
                                <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-3">
                                    Belum punya akun? <a href="{{ route('register', ['redirect' => route('jobs.apply.form', $job->id) ]) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Daftar di sini</a>.
                                </p>
                            @else {{-- Jika user sudah login --}}
                                @if(Auth::user()->role === 'pelamar')
                                    @php
                                        // Cek apakah user sudah melamar via model Application
                                        // Variabel $existingApplication ini sebaiknya dikirim dari Controller JobListingController@show
                                        // Tapi untuk sementara, kita bisa cek di sini.
                                        // Pastikan variabel $hasApplied dan $applicationId dikirim dari controller jika ingin lebih bersih.
                                        if (!isset($hasApplied)) { // Jika $hasApplied tidak dikirim dari controller
                                            $existingApplication = \App\Models\Application::where('user_id', Auth::id())
                                                                                     ->where('job_id', $job->id)
                                                                                     ->first();
                                            $hasApplied = (bool) $existingApplication;
                                            $applicationId = $existingApplication ? $existingApplication->id : null;
                                        }
                                    @endphp
                                    @if($hasApplied && isset($applicationId))
                                        <a href="{{ route('applications.card', $applicationId) }}"
                                           class="w-full block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block mr-2">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                            </svg>
                                            Anda Sudah Melamar (Lihat Formulir)
                                        </a>
                                    @else
                                        {{-- Tombol ini sekarang mengarah ke form biodata --}}
                                        <a href="{{ route('jobs.apply.form', $job->id) }}"
                                           class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out">
                                            Lamar Sekarang (Isi Biodata)
                                        </a>
                                    @endif
                                @else {{-- Jika role bukan pelamar (misal admin) --}}
                                    <button type="button" disabled
                                            class="w-full block text-center bg-slate-400 dark:bg-slate-600 text-slate-700 dark:text-slate-300 font-bold py-3 px-6 rounded-lg shadow-md text-lg opacity-75 cursor-not-allowed">
                                        Hanya Pelamar yang Bisa Melamar
                                    </button>
                                @endif
                            @endguest
                        @else
                             <button type="button" disabled
                                    class="w-full block text-center bg-red-500 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg opacity-75 cursor-not-allowed">
                                Lowongan Sudah Kedaluwarsa
                            </button>
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
