    {{-- resources/views/admin/articles/_form.blade.php --}}

    {{-- Judul Artikel --}}
    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Artikel <span class="text-red-500">*</span></label>
        <input type="text" name="title" id="title" value="{{ old('title', $article->title ?? '') }}" required
               class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror">
        @error('title')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Gambar Artikel --}}
    <div class="mb-4">
        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Artikel @if(!isset($article->image))<span class="text-red-500">*</span>@endif</label>
        <input type="file" name="image" id="image"
               class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-600 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-500 @error('image') border-red-500 @enderror"
               {{ !isset($article->image) ? 'required' : '' }}>
        @error('image')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        @if(isset($article) && $article->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $article->image) }}" alt="Gambar {{ $article->title }}" class="w-48 h-auto object-cover rounded">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gambar saat ini. Upload baru untuk mengganti.</p>
            </div>
        @endif
    </div>

    {{-- Isi Artikel --}}
    <div class="mb-6">
        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Isi Artikel <span class="text-red-500">*</span></label>
        <textarea name="content" id="content" rows="10" required
                  class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('content') border-red-500 @enderror">{{ old('content', $article->content ?? '') }}</textarea>
        @error('content')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end">
        <a href="{{ route('admin.articles.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2 transition duration-150 ease-in-out">
            Batal
        </a>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
            {{ isset($article) ? 'Update Artikel' : 'Simpan Artikel' }}
        </button>
    </div>
    