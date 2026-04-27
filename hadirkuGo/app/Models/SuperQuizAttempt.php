<?php

// app/Models/SuperQuizAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Make sure Str class is imported (though not strictly needed for this error, good practice)

class SuperQuizAttempt extends Model
{
    use HasFactory;

    // Explicitly set the primary key column to 'unique_id'
    protected $primaryKey = 'unique_id';

    // Indicate that the primary key is not auto-incrementing (it's a UUID)
    public $incrementing = false;

    // Specify the primary key type as string (UUID)
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['unique_id', 'user_id', 'super_quiz_id', 'attempt_date', 'score', 'status']; // Include 'unique_id' in fillable (good practice)


    // Boot method to auto-generate UUID for unique_id
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->unique_id = (string) Str::uuid();
        });
    }

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to SuperQuiz
    public function superQuiz()
    {
        return $this->belongsTo(SuperQuiz::class, 'super_quiz_id');
    }

    // Relationship to SuperQuizResult
    public function results()
    {
        return $this->hasMany(SuperQuizResult::class, 'super_quiz_attempt_id');
    }
}