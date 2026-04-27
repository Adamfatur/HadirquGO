<?php

// app/Models/Option.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'option_letter', 'option_text', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }
}
