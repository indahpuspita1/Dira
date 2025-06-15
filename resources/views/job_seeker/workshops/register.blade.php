@extends('layouts.pelamar') {{-- Atau layout publik yang sesuai --}}
@section('title', 'Daftar Workshop: ' . $workshop->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-xl mx-auto bg-white dark:bg-slate-800 shadow-xl rounded-lg p-6 sm:p-8">
        <div class="mb-6">
            <a href="{{ route('workshops.show', $workshop->id) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Kembali ke Detail Workshop
            </a>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">Form Pendaftaran Workshop</h1>
        <h2 class="text-xl text-indigo-600 dark:text-indigo-400 mb-6">{{ $workshop->title }}</h2>

        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Jadwal: {{ $workshop->date_time->isoFormat('dddd, D MMMM YYYY - HH:mm') }} WIB</p>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Lokasi/Link: {{ $workshop->location_or_link }}</p>
        <p class="text-sm font-semibold {{ $workshop->price > 0 ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }} mb-6">
            Harga: {{ $workshop->price > 0 ? 'Rp ' . number_format($workshop->price, 0, ',', '.') : 'Gratis' }}
        </p>

        <form action="{{ route('workshops.register.store', $workshop->id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon (WhatsApp)</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Contoh: 081234567890"
                           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tambahkan field lain jika perlu --}}

                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        Daftar Workshop
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
