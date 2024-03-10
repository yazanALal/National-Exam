<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_id',
        'question_number'
    ];

    protected $casts = [
        'exam_id' => 'integer',
        'question_id' => 'integer',
        'question_number' => 'integer',
    ];

    public function answerExamQuestions(): object
    {
        return $this->hasMany(AnswerExamQuestion::class);
    }

    public function question(): object
    {
        return $this->belongsTo(Question::class);
    }

    public function exam(): object
    {
        return $this->belongsTo(Exam::class);
    }
}
