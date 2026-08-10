<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'phone_number',
        'address',
        'card_id',
        'email',
    ];
}
