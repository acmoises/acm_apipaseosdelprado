<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SystemPaymentController extends Controller
{
    /**
     * Valida que el usuario tenga privilegios de superadministrador.
     */
    protected function checkSuperAdmin(Request $request)
    {
        $superAdminEmail = strtolower(trim(config('app.superadmin_email', 'moises.lsc.19@gmail.com')));
        $userEmail = strtolower(trim($request->user()->email));

        if ($userEmail !== $superAdminEmail) {
            abort(403, 'No autorizado. Solo el proveedor del software puede gestionar licencias.');
        }
    }

    /**
     * Devuelve el historial de pagos del sistema y sugiere el siguiente periodo a cubrir.
     */
    public function index(Request $request)
    {
        $this->checkSuperAdmin($request);

        $payments = SystemPayment::with('registeredByUser:id,name,email')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Calcular el siguiente periodo sugerido a pagar
        $latestPayment = SystemPayment::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        if ($latestPayment) {
            $nextDate = Carbon::createFromDate($latestPayment->year, $latestPayment->month, 1)->addMonth();
            $suggestedNext = [
                'month' => $nextDate->month,
                'year' => $nextDate->year,
            ];
        } else {
            $now = Carbon::now();
            $suggestedNext = [
                'month' => $now->month,
                'year' => $now->year,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $payments,
            'suggested_next_period' => $suggestedNext,
        ]);
    }

    /**
     * Registra el pago de uno o varios meses (adelantados).
     */
    public function store(Request $request)
    {
        $this->checkSuperAdmin($request);

        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2024|max:2099',
            'advance_months' => 'nullable|integer|min:1|max:24',
            'amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $advanceMonths = (int) ($validated['advance_months'] ?? 1);
        $amountPerMonth = isset($validated['amount']) ? ($validated['amount'] / $advanceMonths) : null;
        $notes = $validated['notes'] ?? null;

        $createdPayments = [];
        $skippedPeriods = [];

        DB::beginTransaction();
        try {
            $cursor = Carbon::createFromDate($validated['year'], $validated['month'], 1);

            for ($i = 0; $i < $advanceMonths; $i++) {
                $targetMonth = $cursor->month;
                $targetYear = $cursor->year;

                $existing = SystemPayment::where('month', $targetMonth)
                    ->where('year', $targetYear)
                    ->first();

                if ($existing) {
                    $skippedPeriods[] = "{$targetMonth}/{$targetYear}";
                } else {
                    $payment = SystemPayment::create([
                        'month' => $targetMonth,
                        'year' => $targetYear,
                        'amount' => $amountPerMonth,
                        'notes' => $notes,
                        'registered_by' => $request->user()->id,
                        'paid_at' => Carbon::now(),
                    ]);

                    // Invalidar la caché de la licencia para este periodo específico
                    Cache::forget("system_license_paid_{$targetYear}_{$targetMonth}");

                    $createdPayments[] = $payment;
                }

                $cursor->addMonth();
            }

            DB::commit();

            $msgParts = [];
            if (count($createdPayments) > 0) {
                $msgParts[] = count($createdPayments) === 1
                    ? '1 mes registrado exitosamente.'
                    : count($createdPayments) . ' meses registrados exitosamente.';
            }
            if (count($skippedPeriods) > 0) {
                $msgParts[] = 'Los periodos (' . implode(', ', $skippedPeriods) . ') ya estaban previamente cubiertos.';
            }

            return response()->json([
                'success' => true,
                'message' => implode(' ', $msgParts) ?: 'No se realizaron cambios.',
                'data' => $createdPayments,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el pago: ' . $e->getMessage(),
            ], 500);
        }
    }
}
