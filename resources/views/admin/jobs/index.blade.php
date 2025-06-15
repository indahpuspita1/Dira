@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utamamu --}}

@section('header', 'Kelola Lowongan Pekerjaan') {{-- Jika layoutmu menggunakan slot header --}}

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Lowongan Pekerjaan</h1>
        <a href="{{ route('admin.jobs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
            Tambah Lowongan Baru
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto">
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
                            @if($job->image)
                                <img src="{{ asset('storage/' . $job->image) }}" alt="{{ $job->title }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-xs italic">Tidak ada gambar</span>
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
                        <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">
                            Tidak ada data lowongan pekerjaan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $jobs->links() }} {{-- Untuk pagination --}}
    </div>
</div>
@endsection
