<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',      // Pelamar yang membuat lamaran
        'job_id',       // Lowongan yang dilamar
        'applicant_name',         // Tambahkan
        'applicant_email',        // Tambahkan
        'applicant_phone',        // Tambahkan
        'address',                // Tambahkan
        'education_level',        // Tambahkan
        'work_experience_summary',// Tambahkan
        'face_photo',             // Tambahkan
        'status',       // Status lamaran (pending, reviewed, dll.)
        'resume',       // Path ke file resume (opsional)
        'cover_letter', // Isi surat lamaran (opsional)
        'applied_at',   // Tanggal melamar
        'application_code',       // Tambahkan
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'applied_at' => 'datetime',
    ];

    // Relasi ke User (Pelamar)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Job (Lowongan yang dilamar)
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
    
}