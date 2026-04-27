<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuperQuiz extends Model
{
    use HasFactory;

    protected $fillable = ['unique_id', 'business_id', 'title', 'max_score', 'question_limit', 'status'];

    // Define the primary key column
    protected $primaryKey = 'unique_id'; // Set the custom primary key column

    public $incrementing = false; // Disable auto-incrementing since it's a UUID

    // Relationship to SuperQuizQuestion
    public function questions()
    {
        return $this->hasMany(SuperQuizQuestion::class, 'super_quiz_id');
    }

    // Relationship to SuperQuizAttempt
    public function attempts()
    {
        return $this->hasMany(SuperQuizAttempt::class, 'super_quiz_id');
    }

    // Relationship to Business
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    // Override the boot method to set a default unique_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($superQuiz) {
            // Generate a UUID for the unique_id field if it's not already set
            if (!$superQuiz->unique_id) {
                $superQuiz->unique_id = Str::uuid()->toString();
            }
        });
    }
}
