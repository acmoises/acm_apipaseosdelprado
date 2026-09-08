<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RosterApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Roster::query();

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year);
        }

        $rosters = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $rosters
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $roster = Roster::create([
            'user_id' => $request->user()->id ?? 1,
            'name' => $request->name,
            'roster_identifier' => 'NOM-' . strtoupper(uniqid()),
            'amount' => $request->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nómina registrada exitosamente',
            'data' => $roster
        ], 201);
    }

    public function generatePdf($id)
    {
        $roster = Roster::find($id);

        if (!$roster) {
            return response()->json([
                'success' => false,
                'message' => 'El pago de nómina especificado no existe'
            ], 404);
        }

        // Generate a unique security hash for the QR
        $securityHash = hash('sha256', $roster->id . $roster->roster_identifier . $roster->amount . config('app.key'));
        $qrText = "VERIFICACIÓN DE PAGO DE NÓMINA\nFolio: {$roster->roster_identifier}\nNombre: {$roster->name}\nMonto: \$" . number_format($roster->amount, 2) . "\nHash de Seguridad: " . substr($securityHash, 0, 16);

        $qrCode = QrCode::create($qrText);
        $writer = new PngWriter();
        $result_qr = $writer->write($qrCode);

        $pdf = Pdf::loadView('pdf-roster', [
            'roster' => $roster,
            'result_qr' => $result_qr,
            'security_hash' => substr($securityHash, 0, 16)
        ]);

        return $pdf->stream("recibo-nomina-{$roster->roster_identifier}.pdf");
    }

    public function cancel($id)
    {
        $roster = Roster::find($id);

        if (!$roster) {
            return response()->json([
                'success' => false,
                'message' => 'El pago de nómina especificado no existe'
            ], 404);
        }

        $roster->status = 'cancelled';
        $roster->save();

        return response()->json([
            'success' => true,
            'message' => 'Pago de nómina cancelado exitosamente',
            'data' => $roster
        ]);
    }
}
