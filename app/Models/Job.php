<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'company',
        'description',
        'location',
        'deadline',
        'image',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    // Admin yang memposting lowongan ini
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Kategori disabilitas untuk lowongan ini
    public function disabilityCategories()
    {
        return $this->belongsToMany(DisabilityCategory::class, 'job_disability_category');
    }

    // Relasi ke Lamaran yang masuk untuk lowongan ini
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    // Relasi untuk mendapatkan daftar User (Pelamar) yang telah melamar lowongan ini
    // Melalui tabel 'applications'
    public function applicants()
    {
        return $this->belongsToMany(User::class, 'applications', 'job_id', 'user_id')
                    ->withPivot('status', 'applied_at', 'application_code',
                    'applicant_name', 'applicant_email', 'applicant_phone',
                    'address', 'education_level', 'work_experience_summary', 'face_photo') // Ambil juga data dari tabel pivot
                    ->withTimestamps(); // Jika tabel pivot punya created_at & updated_at
    }
}