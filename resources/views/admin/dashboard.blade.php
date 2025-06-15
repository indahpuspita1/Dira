{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app') {{-- Sesuaikan dengan nama file layout adminmu jika berbeda --}}

{{-- Jika layoutmu menggunakan slot header seperti Breeze --}}
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Admin Dashboard') }}
    </h2>
</x-slot>

{{-- Jika layoutmu menggunakan @section('header') --}}
{{--
@section('header')
    Admin Dashboard
@endsection
--}}

@section('content') {{-- Atau jika menggunakan komponen layout Breeze, konten utama langsung di dalam <x-app-layout> --}}
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium">Selamat Datang di Dashboard Admin, {{ Auth::user()->name }}!</h3>
                <p class="mt-2">
                    Ini adalah halaman dashboard admin Dira Website. Dari sini kamu bisa mengelola lowongan pekerjaan, artikel, dan workshop.
                </p>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Contoh Kartu Navigasi --}}
                    <a href="{{ route('admin.jobs.index') }}" class="block p-6 bg-indigo-500 dark:bg-indigo-600 hover:bg-indigo-600 dark:hover:bg-indigo-700 text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                        <h4 class="text-xl font-semibold">Kelola Lowongan</h4>
                        <p class="text-sm mt-1">Tambah, edit, atau hapus lowongan pekerjaan.</p>
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="block p-6 bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700 text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                        <h4 class="text-xl font-semibold">Kelola Artikel</h4>
                        <p class="text-sm mt-1">Buat dan publikasikan artikel informatif.</p>
                    </a>
                    <a href="{{ route('admin.workshops.index') }}" class="block p-6 bg-yellow-500 dark:bg-yellow-600 hover:bg-yellow-600 dark:hover:bg-yellow-700 text-white rounded-lg shadow-md transition-transform transform hover:scale-105">
                        <h4 class="text-xl font-semibold">Kelola Workshop</h4>
                        <p class="text-sm mt-1">Atur jadwal dan detail workshop.</p>
                    </a>
                    {{-- Tambahkan kartu lain jika perlu --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection {{-- Tutup @section('content') jika menggunakan struktur @extends --}}