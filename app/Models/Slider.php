<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'position',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'url' => 'json',
        'position' => 'string',
    ];
}
