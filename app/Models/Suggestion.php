<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'text' => 'string',

    ];

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }
}
