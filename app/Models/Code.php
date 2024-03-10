<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'college_id'
    ];

    protected $casts = [
        'code' => 'string',
        'user_id' => 'integer',
        'college_id' => 'integer',
    ];

    public function college(): object
    {
        return $this->belongsTo(College::class);
    }

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }
}
