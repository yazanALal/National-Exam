<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'college_id',
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'college_id' => 'integer',
    ];

    public function college(): object
    {
        return $this->belongsTo(College::class);
    }

    public function exams(): object
    {
        return $this->hasMany(Exam::class);
    }
}
