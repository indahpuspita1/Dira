<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WorkshopRegistration extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id', 'workshop_id', 'name', 'email', 'phone',
        'registration_date', 'status', 'unique_registration_code',
    ];
    protected $casts = ['registration_date' => 'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function workshop() { return $this->belongsTo(Workshop::class); }
}