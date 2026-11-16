<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BillingConfig extends Model
{
    use HasFactory;

    public const DEFAULT_GRACE_DAYS = 5;

    public const MONTH_NAMES = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre',
    ];

    protected $fillable = [
        'month',
        'year',
        'grace_days',
        'notes',
        'updated_by',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'grace_days' => 'integer',
        'updated_by' => 'integer',
    ];

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function getCacheKey(int $year, int $month): string
    {
        return "billing_grace_days_{$year}_{$month}";
    }

    public static function clearPeriodCache(int $year, int $month): void
    {
        Cache::forget(self::getCacheKey($year, $month));
    }

    /**
     * Obtiene la cantidad de días de cobro configurados para el mes/año indicado.
     * Si no existe configuración guardada, devuelve 5 por defecto.
     */
    public static function getDaysForPeriod(?int $month = null, ?int $year = null): int
    {
        $now = Carbon::now();
        $targetMonth = $month ?: $now->month;
        $targetYear = $year ?: $now->year;

        $cacheKey = self::getCacheKey($targetYear, $targetMonth);

        return (int) Cache::remember($cacheKey, 3600, function () use ($targetMonth, $targetYear) {
            try {
                $config = self::where('month', $targetMonth)
                    ->where('year', $targetYear)
                    ->first();

                return $config ? (int) $config->grace_days : self::DEFAULT_GRACE_DAYS;
            } catch (\Exception $e) {
                return self::DEFAULT_GRACE_DAYS;
            }
        });
    }

    /**
     * Devuelve el resumen completo del periodo actual para el control de accesos y pagos.
     */
    public static function getCurrentPeriodInfo(): array
    {
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $daysInMonth = $now->daysInMonth;

        $config = null;
        try {
            $config = self::where('month', $month)
                ->where('year', $year)
                ->first();
        } catch (\Exception $e) {
            $config = null;
        }

        $graceDays = $config ? (int) $config->grace_days : self::DEFAULT_GRACE_DAYS;
        $isCustom = $config !== null && (int) $config->grace_days !== self::DEFAULT_GRACE_DAYS;

        return [
            'month' => $month,
            'month_name' => self::MONTH_NAMES[$month] ?? '',
            'year' => $year,
            'grace_days' => $graceDays,
            'default_days' => self::DEFAULT_GRACE_DAYS,
            'is_custom' => $isCustom,
            'has_record' => $config !== null,
            'notes' => $config?->notes,
            'current_day' => $now->day,
            'days_in_month' => $daysInMonth,
            'is_within_grace_period' => $now->day <= $graceDays,
        ];
    }
}

