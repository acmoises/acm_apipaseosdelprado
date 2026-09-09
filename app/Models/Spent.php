<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'concept',
        'amount',
        'evidence',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
