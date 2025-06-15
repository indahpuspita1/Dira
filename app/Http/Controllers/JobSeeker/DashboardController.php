<?php

    namespace App\Http\Controllers\JobSeeker;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Application; // Untuk riwayat lamaran pekerjaan
    use App\Models\WorkshopRegistration; // Untuk riwayat pendaftaran workshop
    use App\Models\QuizAttempt; // <-- INI YANG DIPERBAIKI: TAMBAHKAN IMPORT INI


    class DashboardController extends Controller
    {
        /**
         * Menampilkan halaman dashboard pelamar.
         *
         * @return \Illuminate\View\View
         */
        public function index()
        {
            $user = Auth::user();

            // Ambil riwayat lamaran pekerjaan pengguna yang sedang login
            // Eager load relasi 'job' untuk menampilkan detail pekerjaan
            $jobApplications = Application::where('user_id', $user->id)
                                        ->with('job') // Asumsi ada relasi 'job' di model Application
                                        ->latest('applied_at') // Urutkan dari yang terbaru melamar
                                        ->paginate(5, ['*'], 'job_applications_page'); // Paginasi untuk lamaran, 5 per halaman

            // Ambil riwayat pendaftaran workshop pengguna yang sedang login
            // Eager load relasi 'workshop' untuk menampilkan detail workshop
            $workshopRegistrations = WorkshopRegistration::where('user_id', $user->id)
                                        ->with('workshop') // Asumsi ada relasi 'workshop' di model WorkshopRegistration
                                        ->latest('registration_date') // Urutkan dari yang terbaru mendaftar
                                        ->paginate(5, ['*'], 'workshop_registrations_page'); // Paginasi untuk workshop, 5 per halaman


            /// -- PENAMBAHAN KODE --
            // Ambil total jumlah dari objek paginator yang sudah ada. Ini lebih efisien.
            $totalApplications = $jobApplications->total();
            $totalWorkshops = $workshopRegistrations->total();
            // --------------------

             // -- PENAMBAHAN KODE BARU --
             // Ambil hasil tes bakat minat terakhir yang diambil pengguna
           $latestQuizAttempt = QuizAttempt::where('user_id', $user->id)
                                        ->latest('completed_at') // Ambil yang paling baru
                                        ->first();
             // ------------------------
            // Kirim semua data yang diperlukan ke view
            return view('job_seeker.dashboard', compact(
                'user',
                'jobApplications',
                'workshopRegistrations',
                'totalApplications', // <-- Variabel baru
                'totalWorkshops',     // <-- Variabel baru
                 'latestQuizAttempt' // <-- TAMBAHKAN VARIABEL BARU INI
            ));
    
        }
    }
    