<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'type',
        'image',
    ];

    protected $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'type' => 'string',
        'image' => 'string',
    ];

    public function codes(): object
    {
        return $this->hasMany(Code::class);
    }

    public function specialties(): object
    {
        return $this->hasMany(Specialty::class);
    }

    public function subjects(): object
    {
        return $this->hasMany(Subject::class);
    }
}
