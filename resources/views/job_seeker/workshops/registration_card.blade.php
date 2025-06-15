@extends('layouts.pelamar') {{-- Atau layout yang sesuai --}}
@section('title', 'Kartu Workshop Anda')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 container-kartu-workshop"> {{-- Kita bisa tambahkan kelas ini untuk styling print khusus jika perlu --}}
    <div class="max-w-md mx-auto">

        @if(session('success'))
            {{-- TAMBAHKAN KELAS no-print DI SINI --}}
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md no-print" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- TAMBAHKAN KELAS no-print DI SINI --}}
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-100 mb-6 text-center no-print">Kartu Peserta Workshop</h1>

        {{-- Ini adalah div konten kartu yang ingin dicetak --}}
        <div class="bg-gradient-to-br from-indigo-600 to-purple-600 dark:from-indigo-700 dark:to-purple-700 text-white p-6 sm:p-8 rounded-xl shadow-2xl relative overflow-hidden kartu-workshop-content">
            {{-- Ornamen background (opsional) --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full filter blur-xl"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-white/5 rounded-full filter blur-md"></div>

            <div class="relative z-10">
                <div class="mb-6 pb-4 border-b border-white/30">
                    <h2 class="text-2xl font-bold tracking-tight">{{ $registration->workshop->title }}</h2>
                    <p class="text-sm opacity-80">Diselenggarakan oleh: {{ $registration->workshop->admin->name ?? 'Tim Dira' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div>
                        <p class="text-xs uppercase opacity-70 tracking-wider">Nama Peserta</p>
                        <p class="text-lg font-semibold">{{ $registration->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase opacity-70 tracking-wider">Email</p>
                        <p class="text-md truncate">{{ $registration->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase opacity-70 tracking-wider">Jadwal Workshop</p>
                        <p class="text-md font-medium">{{ $registration->workshop->date_time->isoFormat('dddd, D MMMM YYYY') }}</p>
                        <p class="text-sm opacity-90">Pukul {{ $registration->workshop->date_time->isoFormat('HH:mm') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase opacity-70 tracking-wider">Lokasi/Link</p>
                        <p class="text-md break-all">
                            @if(filter_var($registration->workshop->location_or_link, FILTER_VALIDATE_URL))
                                <a href="{{ $registration->workshop->location_or_link }}" target="_blank" class="hover:underline">Akses Workshop Disini</a>
                            @else
                                {{ $registration->workshop->location_or_link }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="text-center bg-white/20 dark:bg-black/20 p-3 rounded-lg mt-6">
                    <p class="text-xs uppercase opacity-70 tracking-wider mb-1">Kode Registrasi Anda</p>
                    <p class="text-2xl font-mono font-bold tracking-wider">{{ $registration->unique_registration_code }}</p>
                </div>

                @if($registration->workshop->price > 0 && $registration->status == 'pending_payment')
                <div class="mt-6 p-3 bg-yellow-100 text-yellow-800 text-sm rounded-md text-center">
                    Status Pendaftaran: **Menunggu Pembayaran**. Informasi pembayaran akan kami kirimkan melalui email.
                </div>
                @elseif($registration->status == 'confirmed')
                 <div class="mt-6 p-3 bg-green-100 text-green-800 text-sm rounded-md text-center">
                    Status Pendaftaran: **Terkonfirmasi**. Sampai jumpa di workshop!
                </div>
                @endif
            </div>
        </div>

        {{-- TAMBAHKAN KELAS no-print PADA DIV PEMBUNGKUS TOMBOL INI --}}
        <div class="mt-8 text-center no-print">
            <button onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-150 ease-in-out mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block mr-2">
                  <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.74.14 1.086.286V2.75C16.086 1.23 14.855 0 13.25 0h-6.5C5.145 0 3.914 1.23 3.914 2.75v3.838c.346-.146.709-.24 1.086-.286V2.75ZM2.504 8.465c-.31-.088-.615-.196-.913-.326a.75.75 0 0 0-.976.814 7.57 7.57 0 0 0 .976 5.046A.75.75 0 0 0 2.5 14h15a.75.75 0 0 0 .905-.901 7.57 7.57 0 0 0 .976-5.046.75.75 0 0 0-.976-.814c-.298.13-.603.238-.913.326a25.35 25.35 0 0 0-14.992 0ZM10 10.25a.75.75 0 0 0-1.5 0v2a.75.75 0 0 0 1.5 0v-2Z" clip-rule="evenodd" />
                  <path d="M4 7.5A2.5 2.5 0 0 0 1.5 10v2.5A2.5 2.5 0 0 0 4 15h12a2.5 2.5 0 0 0 2.5-2.5V10A2.5 2.5 0 0 0 16 7.5H4Z" />
                </svg>
                Cetak Kartu
            </button>
            <a href="{{ route('workshops.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                Lihat Workshop Lain
            </a>
        </div>

    </div>
</div>
@endsection