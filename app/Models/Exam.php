<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'specialty_id',
        'degree',
        'type'
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'specialty_id' => 'integer',
        'degree' => 'string',
        'type' => 'string',
    ];

    public function specialty(): object
    {
        return $this->belongsTo(Specialty::class);
    }

    public function examQuestions(): object
    {
        return $this->hasMany(ExamQuestion::class);
    }
}
