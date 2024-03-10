<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'text',
        'subject_id',
    ];

    protected $casts = [
        'uuid' => 'string',
        'text' => 'string',
        'subject_id' => 'integer',
    ];

    public function subject(): object
    {
        return $this->belongsTo(Subject::class);
    }

    public function examQuestions(): object
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function favorites(): object
    {
        return $this->hasMany(Favorite::class);
    }
}
