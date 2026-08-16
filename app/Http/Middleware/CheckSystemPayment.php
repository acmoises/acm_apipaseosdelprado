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

        // Si es el super admin (el proveedor del software), siempre dejamos pasar la petición
        // para que pueda entrar a registrar el pago
        if ($user && $user->email === 'moises.lsc.19@gmail.com') {
            return $next($request);
        }

        $now = Carbon::now();
        
        // Si estamos a día 4 o posterior, verificamos el pago
        if ($now->day >= 4) {
            $paymentExists = SystemPayment::where('month', $now->month)
                                          ->where('year', $now->year)
                                          ->exists();

            if (!$paymentExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Servicio Suspendido. Por favor, póngase en contacto con el proveedor del software.',
                    'error_code' => 'PAYMENT_REQUIRED'
                ], 402); // 402 Payment Required
            }
        }

        return $next($request);
    }
}
