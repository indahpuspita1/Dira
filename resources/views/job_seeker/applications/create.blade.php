@extends('layouts.pelamar') {{-- Pastikan layouts.pelamar.blade.php ada --}}
@section('title', 'Formulir Lamaran Kerja: ' . $job->title)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-2xl mx-auto bg-white dark:bg-slate-800 shadow-xl rounded-lg p-6 sm:p-8">
        <div class="mb-6">
            <a href="{{ route('jobs.show', $job->id) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Kembali ke Detail Lowongan
            </a>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-100 mb-2">Formulir Lamaran Pekerjaan</h1>
        <h2 class="text-xl text-indigo-600 dark:text-indigo-400 mb-6">Untuk Posisi: {{ $job->title }}</h2>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Perusahaan: {{ $job->company }}</p>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Lokasi: {{ $job->location }}</p>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Harap perbaiki error berikut:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jobs.apply.store', $job->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 border-b pb-2 mb-4">Data Diri Pelamar</h3>
                <div>
                    <label for="applicant_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                    <input type="text" name="applicant_name" id="applicant_name" value="{{ old('applicant_name', $user->name ?? '') }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm @error('applicant_name') border-red-500 @enderror">
                    @error('applicant_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="applicant_email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Email Aktif <span class="text-red-500">*</span></label>
                    <input type="email" name="applicant_email" id="applicant_email" value="{{ old('applicant_email', $user->email ?? '') }}" required
                           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm @error('applicant_email') border-red-500 @enderror">
                    @error('applicant_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="applicant_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon/WhatsApp Aktif <span class="text-red-500">*</span></label>
                    <input type="tel" name="applicant_phone" id="applicant_phone" value="{{ old('applicant_phone', $user->phone ?? '') }}" required placeholder="Contoh: 08123456789"
                           class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm @error('applicant_phone') border-red-500 @enderror">
                    @error('applicant_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap Sesuai Domisili <span class="text-red-500">*</span></label>
                    <textarea name="address" id="address" rows="3" required
                              class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="education_level" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                    <select name="education_level" id="education_level" required
                            class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm @error('education_level') border-red-500 @enderror">
                        <option value="">-- Pilih Pendidikan --</option>
                        <option value="SD" {{ old('education_level') == 'SD' ? 'selected' : '' }}>SD Sederajat</option>
                        <option value="SMP" {{ old('education_level') == 'SMP' ? 'selected' : '' }}>SMP Sederajat</option>
                        <option value="SMA/SMK" {{ old('education_level') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK Sederajat</option>
                        <option value="D1" {{ old('education_level') == 'D1' ? 'selected' : '' }}>Diploma 1 (D1)</option>
                        <option value="D2" {{ old('education_level') == 'D2' ? 'selected' : '' }}>Diploma 2 (D2)</option>
                        <option value="D3" {{ old('education_level') == 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                        <option value="S1/D4" {{ old('education_level') == 'S1/D4' ? 'selected' : '' }}>Sarjana (S1/D4)</option>
                        <option value="S2" {{ old('education_level') == 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                        <option value="S3" {{ old('education_level') == 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                        <option value="Lainnya" {{ old('education_level') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('education_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="work_experience_summary" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ringkasan Pengalaman Kerja (Jika ada)</label>
                    <textarea name="work_experience_summary" id="work_experience_summary" rows="4"
                              placeholder="Sebutkan pengalaman kerja relevan secara singkat. Contoh: Staff Administrasi di PT ABC (2020-2022) - Mengelola dokumen dan data perusahaan."
                              class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm @error('work_experience_summary') border-red-500 @enderror">{{ old('work_experience_summary') }}</textarea>
                    @error('work_experience_summary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="face_photo" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Upload Foto Wajah Terbaru (Formal) <span class="text-red-500">*</span></label>
                    <input type="file" name="face_photo" id="face_photo" required accept="image/jpeg,image/png,image/jpg"
                           class="mt-1 block w-full text-sm text-slate-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-600 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-500 @error('face_photo') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB.</p>
                    @error('face_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md text-lg transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        Kirim Lamaran & Biodata
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>