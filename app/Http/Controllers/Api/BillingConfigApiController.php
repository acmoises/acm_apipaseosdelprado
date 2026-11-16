<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BillingConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingConfigApiController extends Controller
{
    /**
     * Devuelve la configuración de los 12 meses del año solicitado (por defecto año en curso),
     * indicando para cada mes si usa el valor por defecto (5 días) o un valor personalizado.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $year = (int) $request->get('year', $now->year);

        if ($year < 2024 || $year > 2099) {
            $year = $now->year;
        }

        $savedConfigs = BillingConfig::with('updatedByUser:id,name,lastname,email')
            ->where('year', $year)
            ->get()
            ->keyBy('month');

        $monthsData = [];

        foreach (BillingConfig::MONTH_NAMES as $monthNumber => $monthName) {
            $daysInMonth = Carbon::createFromDate($year, $monthNumber, 1)->daysInMonth;
            $record = $savedConfigs->get($monthNumber);

            $graceDays = $record ? (int) $record->grace_days : BillingConfig::DEFAULT_GRACE_DAYS;
            $isCustom = $record !== null && (int) $record->grace_days !== BillingConfig::DEFAULT_GRACE_DAYS;

            $monthsData[] = [
                'id' => $record?->id,
                'month' => $monthNumber,
                'month_name' => $monthName,
                'year' => $year,
                'grace_days' => $graceDays,
                'default_days' => BillingConfig::DEFAULT_GRACE_DAYS,
                'days_in_month' => $daysInMonth,
                'is_custom' => $isCustom,
                'has_record' => $record !== null,
                'is_current_month' => ($year === $now->year && $monthNumber === $now->month),
                'notes' => $record?->notes,
                'updated_at' => $record?->updated_at,
                'updated_by_user' => $record?->updatedByUser ? [
                    'id' => $record->updatedByUser->id,
                    'name' => trim($record->updatedByUser->name . ' ' . ($record->updatedByUser->lastname ?? '')),
                    'email' => $record->updatedByUser->email,
                ] : null,
            ];
        }

        return response()->json([
            'success' => true,
            'year' => $year,
            'default_days' => BillingConfig::DEFAULT_GRACE_DAYS,
            'current_period' => BillingConfig::getCurrentPeriodInfo(),
            'data' => $monthsData,
        ]);
    }

    /**
     * Devuelve únicamente la información del periodo actual (mes/año en curso).
     * Utilizado por los módulos de Control de Accesos y Pagos para mostrar el badge informativo.
     */
    public function current()
    {
        return response()->json([
            'success' => true,
            'data' => BillingConfig::getCurrentPeriodInfo(),
        ]);
    }

    /**
     * Guarda o actualiza la cantidad de días de cobro para un mes y año específicos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2024,2099',
            'grace_days' => 'required|integer|min:1|max:31',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de configuración no válidos.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $month = (int) $request->month;
        $year = (int) $request->year;
        $graceDays = (int) $request->grace_days;
        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        if ($graceDays > $daysInMonth) {
            return response()->json([
                'success' => false,
                'message' => "El mes de " . BillingConfig::MONTH_NAMES[$month] . " de {$year} solo tiene {$daysInMonth} días.",
                'errors' => [
                    'grace_days' => ["El número de días no puede ser mayor a {$daysInMonth}."],
                ],
            ], 422);
        }

        $config = BillingConfig::updateOrCreate(
            [
                'month' => $month,
                'year' => $year,
            ],
            [
                'grace_days' => $graceDays,
                'notes' => $request->filled('notes') ? trim($request->notes) : null,
                'updated_by' => $request->user()?->id,
            ]
        );

        BillingConfig::clearPeriodCache($year, $month);
        $config->load('updatedByUser:id,name,lastname,email');

        $monthName = BillingConfig::MONTH_NAMES[$month] ?? $month;

        return response()->json([
            'success' => true,
            'message' => "Configuración guardada: {$monthName} {$year} tendrá {$graceDays} " . ($graceDays === 1 ? 'día' : 'días') . " de cobro (del día 1 al {$graceDays}).",
            'data' => $config,
            'current_period' => BillingConfig::getCurrentPeriodInfo(),
        ]);
    }

    /**
     * Restablece un mes y año al valor por defecto (5 días) eliminando la configuración personalizada.
     */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2024,2099',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Mes o año inválido para restablecer.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $month = (int) $request->month;
        $year = (int) $request->year;

        BillingConfig::where('month', $month)
            ->where('year', $year)
            ->delete();

        BillingConfig::clearPeriodCache($year, $month);

        $monthName = BillingConfig::MONTH_NAMES[$month] ?? $month;
        $defaultDays = BillingConfig::DEFAULT_GRACE_DAYS;

        return response()->json([
            'success' => true,
            'message' => "Se restableció {$monthName} {$year} al valor por defecto ({$defaultDays} días).",
            'current_period' => BillingConfig::getCurrentPeriodInfo(),
        ]);
    }
}

