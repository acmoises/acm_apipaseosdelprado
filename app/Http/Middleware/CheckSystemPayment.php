<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemPayment;
use Illuminate\Support\Carbon;
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

        // Si es el super admin (el proveedor del software) o tiene acceso total, siempre dejamos pasar la petición
        // para que puedan entrar a registrar el pago
        $isSuperAdmin = $user && strtolower(trim($user->email)) === 'moises.lsc.19@gmail.com';
        $hasFullAccess = $user && is_array($user->permissions) && in_array('*', $user->permissions);

        if ($user && ($isSuperAdmin || $hasFullAccess)) {
            return $next($request);
        }

        $now = Carbon::now();
        
        // Si estamos a día 4 o posterior, verificamos el pago
        if ($now->day >= 4) {
            try {
                $paymentExists = SystemPayment::where('month', $now->month)
                                              ->where('year', $now->year)
                                              ->exists();
            } catch (\Exception $e) {
                // Si la tabla no existe o hay error de DB, asumimos que no hay pago
                $paymentExists = false;
            }

            if (!$paymentExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Servicio Suspendido. Por favor, póngase en contacto con el proveedor del software.',
                    'error_code' => 'PAYMENT_REQUIRED'
                ], 403); // 403 Forbidden to prevent WAF CORS stripping
            }
        }

        return $next($request);
    }
}
