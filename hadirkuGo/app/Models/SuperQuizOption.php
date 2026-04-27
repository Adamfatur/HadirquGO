<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuperQuizOption extends Model
{
    use HasFactory;

    protected $fillable = ['super_quiz_question_id', 'option_letter', 'option_text', 'is_correct'];

    // Set custom primary key
    protected $primaryKey = 'unique_id';

    public $incrementing = false; // Disable auto-incrementing

    // Override the boot method to set a default unique_id
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($superQuizOption) {
            // Generate a UUID for the unique_id field if it's not already set
            if (!$superQuizOption->unique_id) {
                $superQuizOption->unique_id = Str::uuid()->toString();
            }
        });
    }

    // Relationship to SuperQuizQuestion
    public function question()
    {
        return $this->belongsTo(SuperQuizQuestion::class, 'super_quiz_question_id');
    }

    // Relationship to SuperQuizResult
    public function results()
    {
        return $this->hasMany(SuperQuizResult::class, 'super_quiz_option_id');
    }
}
