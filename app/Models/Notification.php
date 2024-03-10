<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'college',
        'body',
    ];

    protected $casts = [
        'title' => 'string',
        'college'  => 'string',
        'body' => 'string',
    ];
}
