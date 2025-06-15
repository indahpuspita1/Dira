<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


// Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;

// Controller Pelamar/Publik
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobSeeker\JobListingController; // Untuk daftar & detail lowongan publik
use App\Http\Controllers\JobSeeker\ArticleController as JobSeekerArticleController;
use App\Http\Controllers\JobSeeker\WorkshopController as JobSeekerWorkshopController;
use App\Http\Controllers\JobSeeker\WorkshopRegistrationController;
use App\Http\Controllers\GroqChatController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\JobSeeker\JobApplicationController;
use App\Http\Controllers\JobSeeker\DashboardController as JobSeekerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. RUTE PUBLIK (Tidak Perlu Login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show'); // Detail lowongan publik
Route::get('/articles', [JobSeekerArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [JobSeekerArticleController::class, 'show'])->name('articles.show');
Route::get('/workshops', [JobSeekerWorkshopController::class, 'index'])->name('workshops.index');
Route::get('/workshops/{workshop}', [JobSeekerWorkshopController::class, 'show'])->name('workshops.show');

// Rute untuk Tes Bakat Minat (Publik atau memerlukan login, sesuaikan)
Route::get('/tes-bakat-minat', [QuizController::class, 'startQuiz'])->name('quiz.start');
Route::post('/tes-bakat-minat/submit', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
Route::get('/tes-bakat-minat/hasil', [QuizController::class, 'showResult'])->name('quiz.result');


// 2. RUTE AUTENTIKASI DASAR (Bawaan Breeze atau untuk semua user yang login)
Route::get('/dashboard', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'pelamar') {
            return redirect()->route('pelamar.dashboard');
        }
    }
    return redirect()->route('home'); // Fallback jika role tidak dikenali
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// 3. RUTE KHUSUS ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('jobs', AdminJobController::class);
    Route::resource('articles', AdminArticleController::class);
    Route::resource('workshops', AdminWorkshopController::class);
});


// 4. RUTE KHUSUS PELAMAR (yang memerlukan login)
Route::middleware(['auth', 'role:pelamar'])->group(function () {
    // Dashboard Pelamar
    Route::get('/pelamar/dashboard', [JobSeekerDashboardController::class, 'index'])->name('pelamar.dashboard');

    // Pendaftaran Workshop
    Route::get('/workshops/{workshop}/register', [WorkshopRegistrationController::class, 'create'])->name('workshops.register.form'); // Beri nama yang lebih spesifik
    Route::post('/workshops/{workshop}/register', [WorkshopRegistrationController::class, 'store'])->name('workshops.register.store');
    Route::get('/workshop-pendaftaran/{registration}/kartu', [WorkshopRegistrationController::class, 'showCard'])->name('workshops.registration.card');

    // Melamar Pekerjaan (dengan form biodata)
    Route::get('/jobs/{job}/apply-form', [JobApplicationController::class, 'create'])->name('jobs.apply.form'); // Mengarah ke form biodata
    Route::post('/jobs/{job}/apply-form', [JobApplicationController::class, 'store'])->name('jobs.apply.store'); // Menyimpan biodata & lamaran
    Route::get('/applications/{application}/card', [JobApplicationController::class, 'showCard'])->name('applications.card');

    // Chatbot AI Groq
    Route::get('/konsultasi-ai-groq', function () {
        return view('chatbot.groq');
    })->name('chat.groq.index');
    Route::post('/chat/groq/send', [GroqChatController::class, 'sendMessage'])->name('chat.groq.send');
});


require __DIR__.'/auth.php';