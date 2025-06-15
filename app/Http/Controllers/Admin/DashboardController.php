<?php

namespace App\Http\Controllers\Admin; // Pastikan namespace ini benar

use App\Http\Controllers\Controller; // Import base Controller
use Illuminate\Http\Request;
// Jika kamu ingin menampilkan data di dashboard, import modelnya di sini
// use App\Models\Job;
// use App\Models\Article;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Contoh jika ingin mengambil data untuk ditampilkan:
        // $totalJobs = Job::count();
        // $totalArticles = Article::count();
        // return view('admin.dashboard', compact('totalJobs', 'totalArticles'));

        // Untuk sekarang, cukup tampilkan view dashboard admin
        // Pastikan file resources/views/admin/dashboard.blade.php sudah ada
        return view('admin.dashboard');
    }
}