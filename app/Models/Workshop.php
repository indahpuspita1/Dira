<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id', // Foreign key untuk user (admin) yang membuat workshop
        'title',
        'description',
        'image',       // Path atau nama file gambar workshop
        'date_time',   // Tanggal dan waktu workshop
        'location_or_link',
        'price', // Lokasi fisik atau link online
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_time' => 'datetime',
          'price' => 'decimal:2', // Otomatis konversi ke objek Carbon
    ];

    /**
     * Mendapatkan admin (User) yang memposting workshop ini.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function registrations() 
    {
        return $this->hasMany(WorkshopRegistration::class);
    }
    public function registeredUsers() {
        return $this->belongsToMany(User::class, 'workshop_registrations')->withTimestamps();
    }
}