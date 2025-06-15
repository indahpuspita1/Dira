<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Article; // Import model Article
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar artikel untuk pelamar.
     */
    public function index(Request $request)
    {
        $articlesQuery = Article::latest(); // Ambil artikel, urutkan dari yang terbaru

        // Fitur Pencarian (Opsional, mirip dengan pencarian lowongan)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $articlesQuery->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        $articles = $articlesQuery->paginate(9)->withQueryString();

        // Pastikan kamu sudah membuat view ini: resources/views/job_seeker/articles/index.blade.php
        // atau resources/views/job_seeker/articles/index.blade.php
        return view('job_seeker.articles.index', compact('articles'));
    }

    /**
     * Menampilkan detail satu artikel.
     */
    public function show(Article $article) // Route Model Binding
    {
        // Pastikan kamu sudah membuat view ini: resources/views/job_seeker/articles/show.blade.php
        // atau resources/views/job_seeker/articles/show.blade.php
        return view('job_seeker.articles.show', compact('article'));
    }
}