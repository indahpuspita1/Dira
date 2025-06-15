<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisabilityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relasi Many-to-Many dengan Jobs
    // Satu kategori bisa dimiliki banyak lowongan, satu lowongan bisa punya banyak kategori
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_disability_category');
        // Argumen kedua adalah nama tabel pivot
        // Argumen ketiga (foreignPivotKey) dan keempat (relatedPivotKey) bisa dispesifikkan jika
        // nama kolom di tabel pivot tidak mengikuti konvensi Laravel (misal: disability_category_id, job_id)
    }
}