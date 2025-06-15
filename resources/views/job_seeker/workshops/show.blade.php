@extends('layouts.pelamar') {{-- Atau layout publik yang sesuai --}}
@section('title', $workshop->title . ' - Workshop & Pelatihan')

@section('content')
<div class="bg-slate-50 dark:bg-slate-900 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            <div class="mb-8">
                <a href="{{ route('workshops.index') }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 transition-colors duration-150 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-2">
                        <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Daftar Workshop
                </a>
            </div>

            <article class="bg-white dark:bg-slate-800 shadow-xl rounded-xl overflow-hidden">
                @if($workshop->image)
                    <img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}" class="w-full h-auto max-h-[500px] object-cover">
                @endif

                <div class="p-6 sm:p-8 md:p-10">
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-100 mb-3 leading-tight">{{ $workshop->title }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
                        Diselenggarakan oleh: <span class="font-medium">{{ $workshop->admin->name ?? 'Tim Dira' }}</span>
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-8 pb-6 border-b border-slate-200 dark:border-slate-700">
                        <div>
                            <h3 class="text-base font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Jadwal</h3>
                            <p class="text-lg text-indigo-600 dark:text-indigo-400 font-medium">
                                {{ $workshop->date_time->isoFormat('dddd, D MMMMGTBaseAlert') }}
                            </p>
                            <p class="text-md text-slate-700 dark:text-slate-300">
                                Pukul {{ $workshop->date_time->isoFormat('HH:mm') }} WIB
                            </p>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Lokasi / Platform</h3>
                            <p class="text-lg text-slate-800 dark:text-slate-200 break-all">
                                @if(filter_var($workshop->location_or_link, FILTER_VALIDATE_URL))
                                    <a href="{{ $workshop->location_or_link }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Akses Link Workshop
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 inline-block ml-1">
                                          <path d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z" />
                                          <path d="M3.5 6.75c0-1.036.84-1.875 1.875-1.875h1.5a.75.75 0 0 0 0-1.5h-1.5A3.375 3.375 0 0 0 1.625 6.75v6.5A3.375 3.375 0 0 0 5 16.625h6.5A3.375 3.375 0 0 0 14.875 13.25v-1.5a.75.75 0 0 0-1.5 0v1.5a1.875 1.875 0 0 1-1.875 1.875h-6.5A1.875 1.875 0 0 1 3.5 13.25v-6.5Z" />
                                        </svg>
                                    </a>
                                @else
                                    {{ $workshop->location_or_link }}
                                @endif
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-base font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Biaya Pendaftaran</h3>
                            <p class="text-xl font-bold {{ $workshop->price > 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                                {{ $workshop->price > 0 ? 'Rp ' . number_format($workshop->price, 0, ',', '.') : 'GRATIS' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-3">Deskripsi Workshop</h3>
                        <div class="prose prose-lg prose-slate dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed">
                            {!! nl2br(e($workshop->description)) !!}
                        </div>
                    </div>

                    {{-- Tombol Daftar Workshop --}}
                    <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-700">
                        {{-- Logika ini sebaiknya ada di Controller --}}
                        @php
                            $isRegistered = false;
                            $registrationId = null;
                            $isPast = $workshop->date_time < now();

                            if(Auth::check() && Auth::user()->role == 'pelamar') {
                                $registration = \App\Models\WorkshopRegistration::where('user_id', Auth::id())
                                                                               ->where('workshop_id', $workshop->id)
                                                                               ->first();
                                if ($registration) {
                                    $isRegistered = true;
                                    $registrationId = $registration->id;
                                }
                            }
                        @endphp

                        @if($isPast)
                            <button type="button" disabled class="w-full block text-center bg-red-400 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg opacity-75 cursor-not-allowed">
                                Workshop Sudah Berakhir
                            </button>
                        @else
                            @guest
                                <a href="{{ route('login', ['redirect' => route('workshops.register.form', $workshop->id)]) }}" class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out">
                                    Login untuk Mendaftar
                                </a>
                            @else
                                @if(Auth::user()->role == 'pelamar')
                                    @if($isRegistered)
                                        <a href="{{ route('workshops.registration.card', $registrationId) }}" class="w-full block text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out">
                                            Anda Sudah Terdaftar (Lihat Kartu)
                                        </a>
                                    @else
                                        {{-- >>> INI BAGIAN YANG DIPERBAIKI <<< --}}
                                        <a href="{{ route('workshops.register.form', $workshop->id) }}"
                                           class="w-full block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out">
                                            Daftar Workshop Ini
                                            @if($workshop->price > 0)
                                                (Rp {{ number_format($workshop->price, 0, ',', '.') }})
                                            @else
                                                (Gratis)
                                            @endif
                                        </a>
                                    @endif
                                @else
                                    <button type="button" disabled class="w-full block text-center bg-slate-400 ...">
                                        Hanya Pelamar yang Bisa Mendaftar
                                    </button>
                                @endif
                            @endguest
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
