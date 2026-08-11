<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentCancelled extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'resident_id',
        'payment_type',
        'service_id',
        'amount',
        'payment_identifier',
        'user_id',
        'reason',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
