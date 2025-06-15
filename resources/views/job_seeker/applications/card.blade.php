@extends('layouts.pelamar') {{-- Atau layout publik yang sesuai --}}
@section('title', 'Kartu Formulir Pendaftaran Kerja')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-lg mx-auto"> {{-- Ukuran kartu bisa disesuaikan --}}

        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md no-print" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
         @if(session('warning'))
            <div class="mb-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md shadow-md no-print" role="alert">
                <p class="font-bold">Perhatian!</p>
                <p>{{ session('warning') }}</p>
            </div>
        @endif

        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-100 mb-6 text-center no-print">Kartu Formulir Pendaftaran</h1>

        {{-- Desain Kartu --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-2xl p-6 sm:p-8 border border-indigo-300 dark:border-indigo-700 kartu-formulir-content">
            <div class="text-center mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-xl font-semibold text-indigo-700 dark:text-indigo-300">FORMULIR PENDAFTARAN KERJA</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Dira Jobs</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                @if($application->face_photo)
                    <img src="{{ asset('storage/' . $application->face_photo) }}" alt="Foto {{ $application->applicant_name }}"
                         class="w-28 h-36 sm:w-32 sm:h-40 object-cover rounded-md border-2 border-slate-300 dark:border-slate-600 shadow-md">
                @else
                    <div class="w-28 h-36 sm:w-32 sm:h-40 bg-slate-200 dark:bg-slate-700 flex items-center justify-center rounded-md border-2 border-slate-300 dark:border-slate-600 text-slate-500 dark:text-slate-400">
                        Foto Tidak Ada
                    </div>
                @endif
                <div class="text-center sm:text-left">
                    <p class="text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wider">Nama Pelamar</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-1">{{ $application->applicant_name }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $application->applicant_email }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $application->applicant_phone }}</p>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wider">Melamar Posisi</p>
                <p class="text-lg font-semibold text-slate-700 dark:text-slate-200">{{ $application->job->title }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">di {{ $application->job->company }}</p>
            </div>

            <div class="mb-4">
                <p class="text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wider">Tanggal Lamaran</p>
                <p class="text-md text-slate-700 dark:text-slate-200">{{ $application->applied_at->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB</p>
            </div>

            <div class="text-center bg-slate-100 dark:bg-slate-700 p-3 rounded-lg mt-6">
                <p class="text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wider mb-1">Kode Lamaran Anda</p>
                <p class="text-2xl font-mono font-bold tracking-wider text-indigo-600 dark:text-indigo-400">{{ $application->application_code }}</p>
            </div>

            <div class="mt-6 p-3 bg-green-50 dark:bg-green-900/50 text-green-700 dark:text-green-300 text-sm rounded-md text-center">
                Status Lamaran: <span class="font-semibold">{{ ucfirst($application->status) }}</span>.
                <br>Terima kasih telah melamar. Pantau email Anda untuk informasi selanjutnya.
            </div>
        </div>

        <div class="mt-8 text-center no-print"> {{-- Tombol-tombol ini tidak ikut dicetak --}}
            <button onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out mr-2">
                Cetak Kartu Formulir
            </button>
            <a href="{{ route('jobs.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                Lihat Lowongan Lain
            </a>
        </div>
    </div>
</div>
@endsection
