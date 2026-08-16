<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'month',
        'year',
        'registered_by',
        'paid_at',
    ];
}
