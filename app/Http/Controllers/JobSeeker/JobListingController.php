<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Job; // Pastikan model Job di-import
use App\Models\DisabilityCategory; // Untuk filter berdasarkan kategori
use App\Models\Application; // Import model Application
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk cek user login dan role


class JobListingController extends Controller
{
    /**
     * Menampilkan daftar lowongan pekerjaan untuk pelamar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Query dasar: ambil lowongan yang deadline-nya belum lewat atau hari ini,
        // dan urutkan dari yang terbaru.
        // Eager load relasi untuk performa yang lebih baik (admin, disabilityCategories)
        $jobsQuery = Job::with(['admin', 'disabilityCategories'])
                        ->where('deadline', '>=', now()->toDateString()) // Hanya tanggal, tanpa jam
                        ->latest(); // Urutkan berdasarkan created_at descending

        // Fitur Pencarian (Search Term)
        if ($request->filled('search')) { // filled() mengecek apakah ada value dan tidak kosong
            $searchTerm = $request->input('search');
            $jobsQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('company', 'like', '%' . $searchTerm . '%')
                      ->orWhere('location', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('disabilityCategories', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', '%' . $searchTerm . '%');
                      });
            });
        }

        // Fitur Filter berdasarkan Lokasi (jika ada input filter lokasi)
        if ($request->filled('filter_location')) {
            $jobsQuery->where('location', 'like', '%' . $request->input('filter_location') . '%');
        }

        // Fitur Filter berdasarkan Kategori Disabilitas (jika ada input filter kategori)
        if ($request->filled('filter_disability_category')) {
            $categoryName = $request->input('filter_disability_category');
            $jobsQuery->whereHas('disabilityCategories', function ($query) use ($categoryName) {
                $query->where('name', $categoryName);
            });
        }

        // Ambil hasil query dengan pagination (misalnya 9 lowongan per halaman)
        $jobs = $jobsQuery->paginate(9)->withQueryString(); // withQueryString() agar parameter filter tetap ada di link pagination

        // Ambil semua kategori disabilitas untuk dropdown filter
        $disabilityCategories = DisabilityCategory::orderBy('name')->get();
        // Ambil semua lokasi unik untuk dropdown filter (opsional, bisa jadi banyak)
        $locations = Job::select('location')->distinct()->orderBy('location')->pluck('location');


        // Kirim data ke view
        return view('job_seeker.jobs.index', compact('jobs', 'disabilityCategories', 'locations'));
    }
      public function show(Job $job)
    {
        // Pastikan lowongan masih aktif (deadline belum lewat)
        if ($job->deadline < now()->toDateString()) {
            // abort(404, 'Lowongan ini sudah kedaluwarsa.');
            // Atau redirect dengan pesan error
            return redirect()->route('jobs.index')->with('error', 'Lowongan yang Anda cari sudah kedaluwarsa.');
        }

        // Eager load relasi yang dibutuhkan
        $job->load(['admin', 'disabilityCategories']);

        // Cek apakah user yang sedang login sudah melamar lowongan ini
        $hasApplied = false;
        if (Auth::check() && Auth::user()->role === 'pelamar') {
            $hasApplied = $job->applicants()->where('user_id', Auth::id())->exists();
            // Alternatif jika menggunakan model Application langsung:
            // $hasApplied = Application::where('user_id', Auth::id())->where('job_id', $job->id)->exists();
        }

        return view('job_seeker.jobs.show', compact('job', 'hasApplied'));
    }

    public function apply(Request $request, Job $job)
    {
        // 1. Pastikan user sudah login dan berperan sebagai 'pelamar'
        // Middleware 'auth' dan 'role:pelamar' sebaiknya sudah melindungi route ini
        // Tapi kita bisa tambahkan pengecekan ekstra jika perlu.
        if (!Auth::check() || Auth::user()->role !== 'pelamar') {
            return redirect()->route('login')->with('error', 'Anda harus login sebagai pelamar untuk melamar pekerjaan.');
        }

        // 2. Pastikan lowongan masih aktif (deadline belum lewat)
        if ($job->deadline < now()->toDateString()) {
            return redirect()->route('jobs.show', $job->id)->with('error', 'Maaf, lowongan ini sudah kedaluwarsa.');
        }

        // 3. Cek apakah pelamar sudah pernah melamar lowongan yang sama
        $user = Auth::user();
        $hasApplied = $job->applicants()->where('user_id', $user->id)->exists();
        // Alternatif:
        // $hasApplied = Application::where('user_id', $user->id)->where('job_id', $job->id)->exists();

        if ($hasApplied) {
            return redirect()->route('jobs.show', $job->id)->with('warning', 'Anda sudah pernah melamar pekerjaan ini.');
        }

        // 4. (Opsional) Validasi input tambahan jika ada form lamaran
        // Misalnya, jika ada upload CV atau surat lamaran:
        // $request->validate([
        //     'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        //     'cover_letter' => 'nullable|string|max:5000',
        // ]);
        // $resumePath = null;
        // if ($request->hasFile('resume')) {
        //     $resumePath = $request->file('resume')->store('resumes/' . $user->id, 'private'); // Simpan di storage private
        // }

        // 5. Simpan lamaran ke database
        // Cara 1: Menggunakan relasi many-to-many di model Job (applicants())
        // Ini akan membuat record di tabel pivot 'applications'
        $job->applicants()->attach($user->id, [
            // 'resume' => $resumePath, // Jika ada upload resume
            // 'cover_letter' => $request->input('cover_letter'), // Jika ada cover letter
            'status' => 'pending', // Status awal lamaran
            'applied_at' => now()
        ]);

        // Cara 2: Membuat record baru menggunakan model Application
        // Application::create([
        //     'user_id' => $user->id,
        //     'job_id' => $job->id,
        //     'status' => 'pending',
        //     // 'resume' => $resumePath,
        //     // 'cover_letter' => $request->input('cover_letter'),
        //     'applied_at' => now(),
        // ]);

        // 6. Redirect dengan pesan sukses
        return redirect()->route('jobs.show', $job->id)->with('success', 'Lamaran Anda berhasil dikirim!');
    }
}

 
