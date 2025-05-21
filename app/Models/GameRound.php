<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameRound extends Model
{
    protected $fillable = [
        'started_at',
        'status',
        'drawn_numbers',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'drawn_numbers' => 'array',
    ];

}
