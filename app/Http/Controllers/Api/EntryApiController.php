<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entries;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EntryApiController extends Controller
{
    public function index(Request $request)
    {
        $entries = Entries::with('resident')->orderBy('id', 'desc')->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $entries
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'card_id' => 'required|string',
        ]);

        $resident = Resident::where('card_id', $request->card_id)->first();

        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'Tarjeta o residente no registrado'
            ], 444);
        }

        // 1.- Verificar si la tarjeta está marcada como perdida
        $isLost = DB::table('lost_cards')
            ->where('card_id', $request->card_id)
            ->where('status', 'LOST')
            ->exists();

        if ($isLost) {
            return response()->json([
                'success' => false,
                'message' => 'Tarjeta reportada como perdida',
                'resident' => $resident
            ], 403);
        }

        // 2.- Pase directo
        $pasesDirectos = ["0000271712", "0000220175", "0011276995"];
        if (in_array($resident->card_id, $pasesDirectos)) {
            $this->sendPulseToArduino();
            
            $entry = Entries::create([
                'resident_id' => $resident->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permitir acceso',
                'resident' => $resident,
                'entry' => $entry
            ], 201);
        }

        // 3.- Validaciones de pagos de acuerdo a las fechas
        $day = Carbon::now()->day;

        $pagosActuales = $this->tienePagosActuales($resident->id);
        $pagosMesAnterior = $this->tienePagoMesAnterior($resident->id);

        $allowAccess = false;
        $message = 'Sin servicio, no hay pagos registrados';

        if ($pagosActuales) {
            $allowAccess = true;
            $message = 'Permitir acceso';
        } elseif ($day < 6) {
            if ($pagosMesAnterior) {
                $allowAccess = true;
                $message = 'Permitir acceso. Pago requerido (mes actual)';
            } else {
                $allowAccess = false;
                $message = 'Sin servicio. Revisar historial de pagos';
            }
        } else {
            $allowAccess = false;
            $message = 'Sin servicio. Revisar historial de pagos';
        }

        // Guardar entrada (se guarda el intento aunque no se permita el acceso, igual que en el código anterior)
        $entry = Entries::create([
            'resident_id' => $resident->id,
        ]);

        if (!$allowAccess) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'resident' => $resident,
                'entry' => $entry
            ], 403);
        }

        // Mandar pulso si se permite el acceso
        $this->sendPulseToArduino();

        return response()->json([
            'success' => true,
            'message' => $message,
            'resident' => $resident,
            'entry' => $entry
        ], 201);
    }

    private function tienePagosActuales($residentId)
    {
        $now = Carbon::now();

        return DB::table('payments')
            ->where('resident_id', $residentId)
            ->where('status', '!=', 'CANCELED')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->exists();
    }

    private function tienePagoMesAnterior($residentId)
    {
        $fechaMesAnterior = Carbon::now()->subMonth();

        return DB::table('payments')
            ->where('resident_id', $residentId)
            ->where('status', '!=', 'CANCELED')
            ->whereMonth('created_at', $fechaMesAnterior->month)
            ->whereYear('created_at', $fechaMesAnterior->year)
            ->exists();
    }

    private function sendPulseToArduino()
    {
        $scriptPath = base_path('scripts/sendToArduino.ps1');

        $command = "powershell -ExecutionPolicy Bypass -File \"$scriptPath\" >> " . base_path('scripts/log.txt');

        try {
            shell_exec($command);
        } catch (\Exception $e) {
            Log::error("Error enviando pulso al Arduino: " . $e->getMessage());
        }
    }
}
