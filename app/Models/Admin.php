<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'uuid',
        'email',
        'password',
    ];

    protected $casts = [
        'name' => 'string',
        'uuid' => 'string',
        'email' => 'string',
    ];
}
