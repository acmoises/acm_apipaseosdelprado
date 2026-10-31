<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SystemLicenseController extends Controller
{
    /**
     * Consulta el estado de la licencia y si hay una notificación preventiva activa (del día 25 a fin de mes).
     */
    public function status(Request $request)
    {
        $now = Carbon::now();

        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Verificar si el mes actual ya se encuentra pagado (usando caché para eficiencia)
        $cacheKey = "system_license_paid_{$now->year}_{$now->month}";
        $currentMonthPaid = Cache::remember($cacheKey, 1800, function () use ($now) {
            try {
                return SystemPayment::where('month', $now->month)
                    ->where('year', $now->year)
                    ->exists();
            } catch (\Exception $e) {
                return false;
            }
        });

        // La alerta preventiva solo se activa del día 25 en adelante si el mes en curso NO está pagado
        $isWarningWindow = $now->day >= 25;
        $showWarning = $isWarningWindow && !$currentMonthPaid;

        $message = null;
        if ($showWarning) {
            $currentMonthName = $monthNames[$now->month];
            $nextMonth = $now->copy()->startOfMonth()->addMonth();
            $nextMonthName = $monthNames[$nextMonth->month];
            $endDay = $now->copy()->endOfMonth()->day;

            $message = "Aviso: Tu periodo de {$currentMonthName} concluye el {$endDay} de {$currentMonthName}. Recuerda realizar tu pago para evitar la suspensión del servicio el 1 de {$nextMonthName}.";
        }

        return response()->json([
            'success' => true,
            'warning' => $showWarning,
            'message' => $message,
            'current_period' => [
                'month' => $now->month,
                'year' => $now->year,
                'is_paid' => $currentMonthPaid,
                'day' => $now->day,
                'days_in_month' => $now->daysInMonth,
            ]
        ]);
    }
}
