<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_question_id',
        'answer_id',
        'status'
    ];

    protected $casts = [
        'exam_question_id' => 'integer',
        'answer_id' => 'integer',
        'status' => 'boolean',
    ];

    public function examQuestion(): object
    {
        return $this->belongsTo(ExamQuestion::class);
    }

    public function answer(): object
    {
        return $this->belongsTo(Answer::class);
    }
}
