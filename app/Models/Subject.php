<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'college_id',
        'specialty_id'
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'college_id' => 'integer',
        'specialty_id' => 'integer',
    ];

    public function college(): object
    {
        return $this->belongsTo(College::class);
    }

    
    public function questions(): object
    {
        return $this->hasMany(Question::class);
    }
}
