<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Bypass para el super admin o usuarios con permiso total (*)
        // Esto permite que el proveedor siempre pueda iniciar sesión y registrar el pago.
        $superAdminEmail = strtolower(trim(config('app.superadmin_email', 'moises.lsc.19@gmail.com')));
        $isSuperAdmin = $user && strtolower(trim($user->email)) === $superAdminEmail;
        $hasFullAccess = $user && is_array($user->permissions) && in_array('*', $user->permissions);

        if ($user && ($isSuperAdmin || $hasFullAccess)) {
            return $next($request);
        }

        $now = Carbon::now();

        // 2. Lógica a Mes Vencido (Post-pago):
        // El cliente tiene todo el mes en curso para usar el servicio y pagar.
        // El mes que OBLIGATORIAMENTE debe estar cubierto es el mes anterior.
        $auditPeriod = $now->copy()->subMonth();
        $auditMonth = $auditPeriod->month;
        $auditYear = $auditPeriod->year;

        // 3. Exención del periodo inicial del contrato:
        // Si el mes a auditar es anterior a la fecha de inicio del servicio, se deja pasar.
        $startDateStr = config('app.system_start_date', '2026-08-01');
        try {
            $startDate = Carbon::parse($startDateStr)->startOfMonth();
            if ($auditPeriod->copy()->startOfMonth()->lt($startDate)) {
                return $next($request);
            }
        } catch (\Exception $e) {
            // Si la fecha tiene formato inválido, ignoramos la exención
        }

        // 4. Verificación de pago con Caché (1 hora) para máximo rendimiento
        $cacheKey = "system_license_paid_{$auditYear}_{$auditMonth}";

        $paymentExists = Cache::remember($cacheKey, 3600, function () use ($auditMonth, $auditYear) {
            try {
                return SystemPayment::where('month', $auditMonth)
                                      ->where('year', $auditYear)
                                      ->exists();
            } catch (\Exception $e) {
                return false;
            }
        });

        // 5. Si no se cubrió el mes vencido, suspender el servicio
        if (!$paymentExists) {
            return response()->json([
                'success' => false,
                'message' => 'Servicio Suspendido por falta de pago. Por favor, póngase en contacto con el proveedor del software.',
                'error_code' => 'PAYMENT_REQUIRED',
                'due_period' => [
                    'month' => $auditMonth,
                    'year' => $auditYear,
                ]
            ], 403);
        }

        return $next($request);
    }
}
