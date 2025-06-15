{{-- resources/views/admin/workshops/_form.blade.php --}}

{{-- Judul Workshop --}}
<div class="mb-4">
    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Workshop <span class="text-red-500">*</span></label>
    <input type="text" name="title" id="title" value="{{ old('title', $workshop->title ?? '') }}" required
           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-500 @enderror">
    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Gambar Workshop --}}
<div class="mb-4">
    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar Workshop @if(!isset($workshop->image))<span class="text-red-500">*</span>@endif</label>
    <input type="file" name="image" id="image"
           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-600 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-500 @error('image') border-red-500 @enderror"
           {{ !isset($workshop->image) ? 'required' : '' }}>
    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    @if(isset($workshop) && $workshop->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $workshop->image) }}" alt="{{ $workshop->title }}" class="w-48 h-auto object-cover rounded">
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gambar saat ini. Upload baru untuk mengganti.</p>
        </div>
    @endif
</div>

{{-- Tanggal & Waktu Workshop --}}
<div class="mb-4">
    <label for="date_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal & Waktu <span class="text-red-500">*</span></label>
    <input type="datetime-local" name="date_time" id="date_time" value="{{ old('date_time', isset($workshop->date_time) ? \Carbon\Carbon::parse($workshop->date_time)->format('Y-m-d\TH:i') : '') }}" required
           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('date_time') border-red-500 @enderror">
    @error('date_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Lokasi atau Link --}}
<div class="mb-4">
    <label for="location_or_link" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi / Link Online <span class="text-red-500">*</span></label>
    <input type="text" name="location_or_link" id="location_or_link" value="{{ old('location_or_link', $workshop->location_or_link ?? '') }}" required
           placeholder="Contoh: Gedung Serbaguna Lt. 3 / https://zoom.us/j/xxxx"
           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('location_or_link') border-red-500 @enderror">
    @error('location_or_link') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Harga Workshop --}}
<div class="mb-4">
    <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Workshop (Rp) <span class="text-red-500">*</span></label>
    <input type="number" name="price" id="price" value="{{ old('price', $workshop->price ?? '0.00') }}" required min="0" step="0.01"
           placeholder="Contoh: 50000 (kosongkan atau isi 0 jika gratis)"
           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('price') border-red-500 @enderror">
    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Isi dengan 0 jika workshop ini gratis.</p>
</div>

{{-- Deskripsi Workshop --}}
<div class="mb-6">
    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi Workshop <span class="text-red-500">*</span></label>
    <textarea name="description" id="description" rows="6" required
              class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $workshop->description ?? '') }}</textarea>
    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<div class="flex justify-end">
    <a href="{{ route('admin.workshops.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2 transition duration-150 ease-in-out">
        Batal
    </a>
    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
        {{ isset($workshop) ? 'Update Workshop' : 'Simpan Workshop' }}
    </button>
</div>