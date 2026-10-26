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
        'amount',
        'notes',
        'registered_by',
        'paid_at',
    ];

    public function registeredByUser()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Obtiene el último periodo pagado registrado en el sistema.
     */
    public static function latestPaidPeriod()
    {
        return self::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
    }
}
