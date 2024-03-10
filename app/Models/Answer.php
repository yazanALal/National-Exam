<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'text',
    ];

    protected $casts = [
        'uuid' => 'string',
        'text' => 'string',
    ];

    public function answerExamQuestions(): object
    {
        return $this->hasMany(AnswerExamQuestion::class);
    }
}
