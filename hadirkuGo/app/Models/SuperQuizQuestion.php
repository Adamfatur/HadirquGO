<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuperQuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['super_quiz_id', 'question_text'];

    // Define the primary key column
    protected $primaryKey = 'unique_id';  // Set unique_id as the primary key

    public $incrementing = false;  // Disable auto-incrementing because we use UUID

    // Override the boot method to set a default unique_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($superQuizQuestion) {
            // Generate a UUID for the unique_id field if it's not already set
            if (!$superQuizQuestion->unique_id) {
                $superQuizQuestion->unique_id = Str::uuid()->toString();
            }
        });
    }

    // Relationship to SuperQuiz
    public function superQuiz()
    {
        return $this->belongsTo(SuperQuiz::class, 'super_quiz_id');
    }

    // Relationship to SuperQuizOption
    public function options()
    {
        return $this->hasMany(SuperQuizOption::class, 'super_quiz_question_id');
    }

    // Relationship to SuperQuizResult
    public function results()
    {
        return $this->hasMany(SuperQuizResult::class, 'super_quiz_question_id');
    }
}
