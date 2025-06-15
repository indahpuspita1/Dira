<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Untuk generate kode unik
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class JobApplicationController extends Controller
{
    /**
     * Menampilkan form biodata untuk melamar pekerjaan.
     */
    public function create(Job $job)
    {
        $user = Auth::user();

        // Cek apakah workshop masih bisa didaftar (deadline belum lewat)
        if ($job->deadline < now()->toDateString()) {
             return redirect()->route('jobs.show', $job->id)
                             ->with('error', 'Maaf, lowongan ini sudah kedaluwarsa.');
        }

        // Cek apakah user sudah melamar pekerjaan ini
        $hasApplied = Application::where('user_id', $user->id)
                                 ->where('job_id', $job->id)
                                 ->exists();
        if ($hasApplied) {
            // Jika sudah melamar, mungkin arahkan ke kartu lamarannya jika sudah ada,
            // atau tampilkan pesan bahwa sudah melamar.
            // Untuk sekarang, kita redirect dengan pesan.
            $application = Application::where('user_id', $user->id)->where('job_id', $job->id)->first();
            if ($application && $application->application_code) {
                 return redirect()->route('applications.card', $application->id)
                                 ->with('warning', 'Anda sudah melamar pekerjaan ini. Berikut adalah kartu formulir Anda.');
            }
            return redirect()->route('jobs.show', $job->id)
                             ->with('warning', 'Anda sudah melamar pekerjaan ini.');
        }

        // Pastikan view 'job_seeker.applications.create' sudah dibuat
        return view('job_seeker.applications.create', compact('job', 'user'));
    }

    /**
     * Menyimpan data lamaran (biodata dan foto) ke database.
     */
    public function store(Request $request, Job $job)
    {
        $user = Auth::user();

        // Cek lagi apakah sudah melamar atau lowongan kedaluwarsa
        if ($job->deadline < now()->toDateString()) {
             return redirect()->route('jobs.show', $job->id)
                             ->with('error', 'Maaf, lowongan ini sudah kedaluwarsa.');
        }
        $hasApplied = Application::where('user_id', $user->id)
                                 ->where('job_id', $job->id)
                                 ->exists();
        if ($hasApplied) {
            return redirect()->route('jobs.show', $job->id)
                             ->with('warning', 'Anda sudah melamar pekerjaan ini.');
        }

        $validatedData = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:20', // Jadikan wajib
            'address' => 'required|string|max:1000',        // Jadikan wajib
            'education_level' => 'required|string|max:100', // Jadikan wajib
            'work_experience_summary' => 'nullable|string|max:5000',
            'face_photo' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Wajib, maks 2MB
            // Tambahkan validasi lain jika ada field tambahan di form
        ]);

        $facePhotoPath = null;
        if ($request->hasFile('face_photo')) {
            $facePhotoPath = $request->file('face_photo')->store('face_photos/' . $user->id, 'public');
        }

        // Generate kode aplikasi unik
        $applicationCode = 'APP-' . $job->id . '-' . $user->id . '-' . strtoupper(Str::random(6));
        while (Application::where('application_code', $applicationCode)->exists()) {
            $applicationCode = 'APP-' . $job->id . '-' . $user->id . '-' . strtoupper(Str::random(6));
        }

        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'applicant_name' => $validatedData['applicant_name'],
            'applicant_email' => $validatedData['applicant_email'],
            'applicant_phone' => $validatedData['applicant_phone'],
            'address' => $validatedData['address'],
            'education_level' => $validatedData['education_level'],
            'work_experience_summary' => $validatedData['work_experience_summary'] ?? null,
            'face_photo' => $facePhotoPath,
            'status' => 'pending', // Status awal lamaran
            'applied_at' => now(),
            'application_code' => $applicationCode,
        ]);

        // Redirect ke halaman kartu formulir pendaftaran
        return redirect()->route('applications.card', $application->id)
                         ->with('success', 'Formulir lamaran Anda berhasil dikirim! Berikut adalah kartu pendaftaran Anda.');
    }

    /**
     * Menampilkan kartu formulir pendaftaran.
     */
    public function showCard(Application $application) // Route Model Binding
    {
        // Pastikan user yang login adalah pemilik lamaran ini atau admin
        if (Auth::id() !== $application->user_id && !(Auth::check() && Auth::user()->role === 'admin')) {
            abort(403, 'Anda tidak memiliki akses ke kartu pendaftaran ini.');
        }

        $application->load(['user', 'job']); // Eager load relasi

        // Pastikan view 'job_seeker.applications.card' sudah dibuat
        return view('job_seeker.applications.card', compact('application'));
    }
}
