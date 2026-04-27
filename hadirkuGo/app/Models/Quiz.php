<?php

// app/Models/Quiz.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'title'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($quiz) {
            $quiz->unique_id = Str::uuid()->toString();
        });
    }
}
