    @extends('layouts.app') {{-- Sesuaikan dengan layout adminmu --}}

    @section('header', 'Kelola Artikel')

    @section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Daftar Artikel</h1>
            <a href="{{ route('admin.articles.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                Tambah Artikel Baru
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">Gambar</th>
                        <th class="py-3 px-6 text-left">Judul</th>
                        <th class="py-3 px-6 text-left">Admin</th>
                        <th class="py-3 px-6 text-left">Tanggal Dibuat</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-400 text-sm font-light">
                    @forelse ($articles as $index => $article)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $articles->firstItem() + $index }}</td>
                            <td class="py-3 px-6 text-left">
                                @if($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <span class="text-xs italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-left">{{ Str::limit($article->title, 50) }}</td>
                            <td class="py-3 px-6 text-left">{{ $article->admin->name ?? 'N/A' }}</td>
                            <td class="py-3 px-6 text-left">{{ $article->created_at->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <a href="{{ route('admin.articles.show', $article->id) }}" class="text-blue-500 hover:text-blue-700 font-semibold py-1 px-3 rounded text-xs">
                                        Lihat
                                    </a>
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold py-1 px-3 rounded text-xs">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');" class="inline-block">
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
                                Tidak ada data artikel.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $articles->links() }} {{-- Untuk pagination --}}
        </div>
    </div>
    @endsection
    