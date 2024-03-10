<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_question_id',
        'question_id',
        'uuid',
    ];

    protected $casts = [
        'exam_question_id' => 'integer',
        'user_id' => 'integer',
        'uuid' => 'string',
    ];

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }

    public function question(): object
    {
        return $this->belongsTo(Question::class);
    }

    public function examQuestion(): object
    {
        return $this->belongsTo(ExamQuestion::class);
    }
}
