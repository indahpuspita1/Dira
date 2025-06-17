@extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}
@section('header', 'Kelola Workshop')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Workshop</h1>
        <a href="{{ route('admin.workshops.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tambah Workshop Baru
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm">
                    <th class="py-3 px-6 text-left">No</th>
                    <th class="py-3 px-6 text-left">Gambar</th>
                    <th class="py-3 px-6 text-left">Judul</th>
                    <th class="py-3 px-6 text-left">Tanggal & Waktu</th>
                    <th class="py-3 px-6 text-left">Lokasi/Link</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 dark:text-gray-400 text-sm">
                @forelse ($workshops as $index => $workshop)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="py-3 px-6 text-left">{{ $workshops->firstItem() + $index }}</td>
                        <td class="py-3 px-6 text-left">
                            @if($workshop->image)
                                <img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}" class="w-16 h-16 object-cover rounded">
                            @else N/A @endif
                        </td>
                        <td class="py-3 px-6 text-left">{{ Str::limit($workshop->title, 40) }}</td>
                        <td class="py-3 px-6 text-left">{{ $workshop->date_time->isoFormat('D MMM YY, HH:mm') }}</td>
                        <td class="py-3 px-6 text-left">{{ Str::limit($workshop->location_or_link, 30) }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                <a href="{{ route('admin.workshops.show', $workshop->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold py-1 px-2 rounded text-xs">Lihat</a>
                                <a href="{{ route('admin.workshops.edit', $workshop->id) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold py-1 px-2 rounded text-xs">Edit</a>
                                <form action="{{ route('admin.workshops.destroy', $workshop->id) }}" method="POST" onsubmit="return confirm('Yakin hapus workshop ini?');" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold py-1 px-2 rounded text-xs">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">Tidak ada data workshop.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $workshops->links() }}</div>
</div>
@endsection