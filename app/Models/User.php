<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        "phone",
        'image',
        'device',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'uuid' => 'string',
        'image' => 'string',
        'device' => 'string',
        'phone' => 'string',
    ];

    public function suggestions(): object
    {
        return $this->hasMany(Suggestion::class);
    }

    public function favorites(): object
    {
        return $this->hasMany(Favorite::class);
    }

    public function codes(): object
    {
        return $this->hasMany(Code::class);
    }
}
