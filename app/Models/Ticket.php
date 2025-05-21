<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'round_id',
        'numbers',
        'hits',
        'payout',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'numbers' => 'array',
    ];

    /**
     * Get the user who owns the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the game round associated with the ticket.
     */
    public function round()
    {
        return $this->belongsTo(GameRound::class);
    }
}
