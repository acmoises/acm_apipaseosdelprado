<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entries extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }
}
