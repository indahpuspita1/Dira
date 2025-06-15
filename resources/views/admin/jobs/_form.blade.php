{{-- resources/views/admin/jobs/_form.blade.php --}}
@csrf {{-- CSRF Token ditaruh di form utama (create/edit) --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Kolom Kiri --}}
    <div>
        {{-- Judul Lowongan --}}
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Lowongan <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $job->title ?? '') }}" required class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nama Perusahaan --}}
        <div class="mb-4">
            <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
            <input type="text" name="company" id="company" value="{{ old('company', $job->company ?? '') }}" required class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('company') border-red-500 @enderror">
            @error('company')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Lokasi --}}
        <div class="mb-4">
            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi <span class="text-red-500">*</span></label>
            <input type="text" name="location" id="location" value="{{ old('location', $job->location ?? '') }}" required class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('location') border-red-500 @enderror">
            @error('location')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Deadline --}}
        <div class="mb-4">
            <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deadline <span class="text-red-500">*</span></label>
            <input type="date" name="deadline" id="deadline" value="{{ old('deadline', isset($job->deadline) ? (\Carbon\Carbon::parse($job->deadline)->format('Y-m-d')) : '') }}" required class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('deadline') border-red-500 @enderror">
            @error('deadline')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Gambar Lowongan --}}
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Lowongan @if(!isset($job->image))<span class="text-red-500">*</span>@endif</label>
            <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-600 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-500 @error('image') border-red-500 @enderror" {{ !isset($job->image) ? 'required' : '' }}>
            @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @if(isset($job) && $job->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $job->image) }}" alt="Gambar {{ $job->title }}" class="w-32 h-32 object-cover rounded">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gambar saat ini. Upload baru untuk mengganti.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div>
        {{-- Kategori Disabilitas --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori Disabilitas <span class="text-red-500">*</span></label>
            @if(isset($disabilityCategories) && $disabilityCategories->count() > 0)
                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                @foreach ($disabilityCategories as $category)
                    <div class="flex items-center">
                        <input id="category_{{ $category->id }}" name="disability_categories[]" type="checkbox" value="{{ $category->id }}"
                               class="h-4 w-4 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 dark:bg-gray-700"
                               @if( (is_array(old('disability_categories')) && in_array($category->id, old('disability_categories'))) || (isset($job) && $job->disabilityCategories->contains($category->id)) )
                                   checked
                               @endif
                        >
                        <label for="category_{{ $category->id }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">{{ $category->name }}</label>
                    </div>
                @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada kategori disabilitas. Silakan tambahkan terlebih dahulu.</p>
            @endif
            @error('disability_categories')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('disability_categories.*') {{-- Untuk error validasi array --}}
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- Deskripsi Lowongan --}}
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Lowongan <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="8" required class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $job->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


<div class="mt-8 flex justify-end">
    <a href="{{ route('admin.jobs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2 transition duration-150 ease-in-out">
        Batal
    </a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
        {{ isset($job) ? 'Update Lowongan' : 'Simpan Lowongan' }}
    </button>
</div>
