<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'quiz_unique_id', 'attempt_date', 'score', 'unique_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_unique_id', 'unique_id');
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($attempt) {
            $attempt->unique_id = Str::uuid()->toString(); // Generate UUID
        });
    }
}