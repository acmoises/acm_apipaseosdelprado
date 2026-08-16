<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemPayment;
use Illuminate\Support\Carbon;

class SystemPaymentController extends Controller
{
    /**
     * Devuelve el historial de pagos del sistema.
     */
    public function index(Request $request)
    {
        // Solo permitir si es el super admin
        if ($request->user()->email !== 'moises.lsc.19@gmail.com') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $payments = SystemPayment::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Registra el pago del mes actual.
     */
    public function store(Request $request)
    {
        // Solo permitir si es el super admin
        if ($request->user()->email !== 'moises.lsc.19@gmail.com') {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $now = Carbon::now();

        // Verificar si ya está pagado este mes
        $existing = SystemPayment::where('month', $now->month)
                                 ->where('year', $now->year)
                                 ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'El pago de este mes ya se encuentra registrado.'
            ], 400);
        }

        $payment = SystemPayment::create([
            'month' => $now->month,
            'year' => $now->year,
            'registered_by' => $request->user()->id,
            'paid_at' => $now
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado correctamente. El sistema ha sido habilitado.',
            'data' => $payment
        ], 201);
    }
}
