<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika menggunakan Sanctum untuk API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // HasApiTokens mungkin ada jika Breeze diinstal

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan 'role' ada di sini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash password saat diset
    ];

    // Relasi jika User adalah Admin yang memposting Lowongan
    public function postedJobs()
    {
        return $this->hasMany(Job::class, 'admin_id');
    }

    // Relasi jika User adalah Admin yang memposting Artikel
    public function postedArticles()
    {
        return $this->hasMany(Article::class, 'admin_id');
    }

    // Relasi jika User adalah Admin yang memposting Workshop
    public function postedWorkshops()
    {
        return $this->hasMany(Workshop::class, 'admin_id');
    }

    // Relasi ke Lamaran yang dibuat oleh User (sebagai Pelamar)
    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id');
    }

    // Relasi untuk mendapatkan daftar Lowongan yang telah dilamar oleh User ini
    // Melalui tabel 'applications'
    public function appliedJobs()
    {
        return $this->belongsToMany(Job::class, 'applications', 'user_id', 'job_id')
                    ->withPivot('status', 'applied_at', 'application_code',
                    'applicant_name', 'applicant_email', 'applicant_phone',
                    'address', 'education_level', 'work_experience_summary', 'face_photo') // Ambil juga data dari tabel pivot
                    ->withTimestamps(); // Jika tabel pivot punya created_at & updated_at
    }

    public function workshopRegistrations() 
    {
        return $this->hasMany(WorkshopRegistration::class);
    }
    public function registeredWorkshops() 
    {
        return $this->belongsToMany(Workshop::class, 'workshop_registrations')->withTimestamps();
    }
}