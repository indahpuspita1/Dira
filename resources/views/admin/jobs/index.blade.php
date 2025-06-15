@extends('layouts.app') {{-- Sesuaikan dengan nama file layout adminmu --}}

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Kelola Lowongan Pekerjaan') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Daftar Lowongan Pekerjaan</h1>
                    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Tambah Lowongan Baru
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-800 border-l-4 border-green-500 rounded-r-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="shadow-md rounded-lg overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-left">Gambar</th>
                                <th class="py-3 px-6 text-left">Judul</th>
                                <th class="py-3 px-6 text-left">Perusahaan</th>
                                <th class="py-3 px-6 text-left">Deadline</th>
                                <th class="py-3 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-400 text-sm font-light">
                            @forelse ($jobs as $index => $job)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $jobs->firstItem() + $index }}</td>
                                    <td class="py-3 px-6 text-left">
                                        {{-- INI CARA MENAMPILKAN GAMBAR DARI CLOUDINARY --}}
                                        @if($job->image)
                                            {{-- Langsung panggil URL dari database --}}
                                            <img src="{{ $job->image }}" alt="{{ $job->title }}" class="w-16 h-16 object-cover rounded shadow-md">
                                        @else
                                            {{-- Fallback jika tidak ada gambar --}}
                                            <div class="w-16 h-16 flex items-center justify-center bg-gray-200 dark:bg-gray-700 rounded text-xs text-gray-500">
                                                No Img
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $job->title }}</td>
                                    <td class="py-3 px-6 text-left">{{ $job->company }}</td>
                                    <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($job->deadline)->isoFormat('D MMMM YYYY') }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center space-x-2">
                                            <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold py-1 px-3 rounded text-xs">
                                                Lihat
                                            </a>
                                            <a href="{{ route('admin.jobs.edit', $job->id) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold py-1 px-3 rounded text-xs">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold py-1 px-3 rounded text-xs">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                        Tidak ada data lowongan pekerjaan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $jobs->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
