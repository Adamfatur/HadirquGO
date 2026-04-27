<?php

// app/Models/SuperQuizResult.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import the Str class

class SuperQuizResult extends Model
{
    use HasFactory;

    // Specify that 'unique_id' is the primary key
    protected $primaryKey = 'unique_id';

    // Specify that primary key is not auto-incrementing (it's a UUID)
    public $incrementing = false;

    // Specify the key type is UUID
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['unique_id', 'super_quiz_attempt_id', 'super_quiz_question_id', 'super_quiz_option_id', 'is_correct']; // Make sure 'unique_id' is in fillable

    // Boot method to automatically generate UUID when creating a new record
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unique_id = (string) Str::uuid();
        });
    }

    // Relationship to SuperQuizAttempt
    public function superQuizAttempt()
    {
        return $this->belongsTo(SuperQuizAttempt::class, 'super_quiz_attempt_id');
    }

    // Relationship to SuperQuizQuestion
    public function superQuizQuestion()
    {
        return $this->belongsTo(SuperQuizQuestion::class, 'super_quiz_question_id');
    }

    // Relationship to SuperQuizOption
    public function superQuizOption()
    {
        return $this->belongsTo(SuperQuizOption::class, 'super_quiz_option_id');
    }
}