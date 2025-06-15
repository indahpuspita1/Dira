@extends('layouts.pelamar') {{-- Pastikan ini layout yang benar untuk pelamar --}}
@section('title', 'Dashboard Saya')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header Selamat Datang --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-slate-800 dark:text-slate-100">
                Dashboard Pelamar
            </h1>
            <p class="text-lg text-slate-600 dark:text-slate-400 mt-1">
                Selamat datang kembali, <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $user->name }}</span>!
            </p>
        </div>

        {{-- Kartu Statistik --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            {{-- Total Lamaran Terkirim --}}
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex items-center space-x-4">
                <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900/50 p-3 rounded-full">
                    <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Lamaran Terkirim</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalApplications }}</p>
                </div>
            </div>

            {{-- Total Workshop Diikuti --}}
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex items-center space-x-4">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <svg class="w-7 h-7 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Workshop Diikuti</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $totalWorkshops }}</p>
                </div>
            </div>
             {{-- Bisa tambahkan kartu lain di sini, misal "Sertifikat Diperoleh", dll --}}
             {{-- >>> PENAMBAHAN KARTU KETIGA: TES BAKAT MINAT <<< --}}
            <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
                @if($latestQuizAttempt && !empty($latestQuizAttempt->interest_scores))
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.871m0 0a3.001 3.001 0 0 0 3.182 0M12 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Minat Dominan Anda</p>
                        </div>
                        @php
                            $dominantInterests = array_keys(array_slice($latestQuizAttempt->interest_scores, 0, 2, true));
                        @endphp
                        <p class="text-xl font-bold text-slate-900 dark:text-slate-100 truncate" title="{{ implode(', ', $dominantInterests) }}">
                            {{ implode(', ', $dominantInterests) }}
                        </p>
                    </div>
                    <a href="{{ route('quiz.result', ['attemptId' => $latestQuizAttempt->id]) }}" class="mt-4 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat Hasil Tes Terakhir &rarr;
                    </a>
                @else
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.871m0 0a3.001 3.001 0 0 0 3.182 0M12 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Tes Bakat Minat</p>
                        </div>
                        <p class="text-slate-800 dark:text-slate-100">
                           Temukan potensimu sekarang!
                        </p>
                    </div>
                    <a href="{{ route('quiz.start') }}" class="mt-4 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                        Ambil Tes Sekarang &rarr;
                    </a>
                @endif
            </div>
        </section>

        {{-- BAGIAN RIWAYAT LAMARAN PEKERJAAN (HORIZONTAL CARDS) --}}
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-6">
                Riwayat Lamaran Terbaru
            </h2>
            @if($jobApplications->count() > 0)
                <div class="flex overflow-x-auto space-x-6 pb-4">
                    @foreach($jobApplications as $application)
                    <div class="flex-shrink-0 w-80 min-w-[320px] bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col hover:shadow-xl transition-shadow duration-300">
                        {{-- Gambar Lowongan --}}
                        <a href="{{ route('jobs.show', $application->job->id) }}" class="block h-40 bg-slate-200 dark:bg-slate-700">
                            @if($application->job->image)
                                <img src="{{ asset('storage/' . $application->job->image) }}" alt="{{ $application->job->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500">Gambar tidak tersedia</div>
                            @endif
                        </a>

                        {{-- Info Lamaran --}}
                        <div class="p-4 flex-grow flex flex-col">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 leading-tight truncate" title="{{ $application->job->title }}">
                                <a href="{{ route('jobs.show', $application->job->id) }}" class="hover:text-indigo-600">{{ $application->job->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">{{ $application->job->company }}</p>

                            <div class="text-xs text-slate-500 dark:text-slate-500 mt-auto pt-3 border-t border-slate-200 dark:border-slate-700">
                                Dilamar pada: {{ $application->applied_at->isoFormat('D MMM YY') }}
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($application->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800/50 dark:text-yellow-200
                                    @elseif($application->status == 'accepted' || $application->status == 'interview') bg-green-100 text-green-800 dark:bg-green-800/50 dark:text-green-200
                                    @elseif($application->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-800/50 dark:text-red-200
                                    @else bg-slate-100 text-slate-800 dark:bg-slate-600 dark:text-slate-200 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                                @if($application->application_code)
                                <a href="{{ route('applications.card', $application->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                    Lihat Kartu
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                 {{-- Link untuk melihat semua riwayat --}}
                @if($jobApplications->total() > $jobApplications->count())
                <div class="mt-4 text-right">
                    {{-- TODO: Buat halaman khusus untuk semua riwayat lamaran --}}
                    <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua Riwayat Lamaran &rarr;</a>
                </div>
                @endif
            @else
                <div class="text-center py-8 px-6 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-dashed">
                    <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">Anda Belum Melamar Pekerjaan</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Mulai petualangan karir Anda sekarang.</p>
                    <div class="mt-6">
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Cari Lowongan
                        </a>
                    </div>
                </div>
            @endif
        </section>

        {{-- BAGIAN RIWAYAT PENDAFTARAN WORKSHOP (Tampilan tetap vertikal, tapi dipercantik) --}}
        <section>
            <h2 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-6">
                Riwayat Workshop Terbaru
            </h2>
            @if($workshopRegistrations->count() > 0)
                <div class="space-y-4">
                    @foreach($workshopRegistrations as $registration)
                    <div class="bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-lg shadow-md border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="hidden sm:block flex-shrink-0 bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                               <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 0-1.06-1.06L15 8.94V6.75a.75.75 0 0 0-1.5 0v4.5a.75.75 0 0 0 .75.75h4.5a.75.75 0 0 0 0-1.5h-2.55l4.72-4.72a.75.75 0 0 0-1.06-1.06L15 8.94V6.75a.75.75 0 0 0-1.5 0v4.5a.75.75 0 0 0 .75.75h4.5a.75.75 0 0 0 0-1.5h-2.55Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25vA2.25 2.25 0 0 1 12.75 3h2.25a2.25 2.25 0 0 1 2.25 2.25v2.25a2.25 2.25 0 0 1-2.25 2.25V13.5a8.25 8.25 0 0 1-8.25 8.25H3.75a2.25 2.25 0 0 1-2.25-2.25V13.5h.75Z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-slate-100">
                                    <a href="{{ route('workshops.show', $registration->workshop->id) }}" class="hover:underline">{{ $registration->workshop->title }}</a>
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Jadwal: {{ $registration->workshop->date_time->isoFormat('D MMM YY, HH:mm') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-4 mt-2 sm:mt-0">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($registration->status == 'confirmed') bg-green-100 text-green-800 dark:bg-green-800/50 dark:text-green-200
                                @elseif($registration->status == 'pending_payment') bg-yellow-100 text-yellow-800 dark:bg-yellow-800/50 dark:text-yellow-200
                                @else bg-slate-100 text-slate-800 dark:bg-slate-600 dark:text-slate-200 @endif">
                                {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                            </span>
                            @if($registration->unique_registration_code)
                            <a href="{{ route('workshops.registration.card', $registration->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium whitespace-nowrap">
                                Lihat Kartu
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                 {{-- Link untuk melihat semua riwayat --}}
                 @if($workshopRegistrations->total() > $workshopRegistrations->count())
                 <div class="mt-4 text-right">
                     {{-- TODO: Buat halaman khusus untuk semua riwayat workshop --}}
                     <a href="#" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua Riwayat Workshop &rarr;</a>
                 </div>
                 @endif
            @else
                <div class="text-center py-8 px-6 bg-white dark:bg-slate-800 rounded-lg shadow-md border border-dashed">
                    <h3 class="mt-2 text-lg font-medium text-slate-900 dark:text-slate-200">Anda Belum Mendaftar Workshop Apapun</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Lihat workshop menarik untuk meningkatkan keahlianmu.</p>
                    <div class="mt-6">
                        <a href="{{ route('workshops.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Lihat Workshop
                        </a>
                    </div>
                </div>
            @endif
        </section>

    </div>
</div>
@endsection
