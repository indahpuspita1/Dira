<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id', // Foreign key untuk user yang membuat artikel
        'title',
        'content',
        'image',
    ];

    // Relasi One-to-Many (Inverse): Satu Artikel dimiliki oleh satu Admin (User)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}