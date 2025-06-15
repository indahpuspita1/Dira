<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Workshop; // Import model Workshop
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    /**
     * Menampilkan daftar workshop untuk pelamar.
     */
    public function index(Request $request)
    {
        $workshopsQuery = Workshop::where('date_time', '>=', now()) // Ambil workshop yang akan datang
                                  ->orderBy('date_time', 'asc');

        // Fitur Pencarian (Opsional)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $workshopsQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $workshops = $workshopsQuery->paginate(9)->withQueryString();

        // Pastikan kamu sudah membuat view ini: resources/views/job_seeker/workshops/index.blade.php
        // atau resources/views/job_seeker/workshops/index.blade.php
        return view('job_seeker.workshops.index', compact('workshops'));
    }

    /**
     * Menampilkan detail satu workshop.
     */
    public function show(Workshop $workshop) // Route Model Binding
    {
        // Pastikan kamu sudah membuat view ini: resources/views/job_seeker/workshops/show.blade.php
        // atau resources/views/job_seeker/workshops/show.blade.php
        return view('job_seeker.workshops.show', compact('workshop'));
    }
}