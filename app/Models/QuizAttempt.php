<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class QuizAttempt extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'answers_data', 'result_summary', 'interest_scores', 'completed_at'
    ];
    protected $casts = [
        'answers_data' => 'array',
        'interest_scores' => 'array',
        'completed_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}