<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PaymentReceiptMail;
use App\Models\Payment;
use App\Models\PaymentCancelled;
use App\Models\Resident;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['resident', 'service', 'user']);

        if ($request->filled('resident_id')) {
            $query->where('resident_id', $request->resident_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_identifier', 'like', "%{$search}%")
                  ->orWhereHas('resident', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%")
                        ->orWhere('paternal_surname', 'like', "%{$search}%")
                        ->orWhere('maternal_surname', 'like', "%{$search}%")
                        ->orWhere('card_id', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                  })
                  ->orWhereHas('service', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    public function services()
    {
        $services = Service::all();
        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resident_id' => 'required|exists:residents,id',
            'payment_type' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Anti-duplicate protection: check if identical payment was created in last 20 seconds
        $recentDuplicate = Payment::where('resident_id', $request->resident_id)
            ->where('service_id', $request->service_id)
            ->where('amount', $request->amount)
            ->where('created_at', '>=', \Carbon\Carbon::now()->subSeconds(20))
            ->first();

        if ($recentDuplicate) {
            return response()->json([
                'success' => true,
                'message' => 'El pago ya fue registrado anteriormente.',
                'data' => $recentDuplicate->load(['resident', 'service'])
            ], 200);
        }

        $paymentIdentifier = 'PAY-' . strtoupper(uniqid());

        $payment = Payment::create([
            'resident_id' => $request->resident_id,
            'payment_type' => $request->payment_type,
            'service_id' => $request->service_id,
            'amount' => $request->amount,
            'payment_identifier' => $paymentIdentifier,
            'user_id' => $request->user()->id ?? 1,
        ]);

        $payment->load(['resident', 'service']);

        // Send PDF receipt via email if resident email exists
        if ($payment->resident && !empty($payment->resident->email)) {
            try {
                $residentName = trim("{$payment->resident->name} {$payment->resident->paternal_surname} {$payment->resident->maternal_surname}");

                $qrText = "Folio: {$payment->id}\nIdentificador: {$payment->payment_identifier}\nResidente: {$residentName}\nMonto: \$" . number_format($payment->amount, 2);

                $qrCode = QrCode::create($qrText);
                $writer = new PngWriter();
                $result_qr = $writer->write($qrCode);

                $pdf = Pdf::loadView('pdf', [
                    'payment' => $payment,
                    'resident' => $residentName,
                    'payment_type' => $payment->payment_type ?? 'Mantenimiento',
                    'service' => $payment->service ? $payment->service->name : ($payment->payment_type ?? 'Mantenimiento'),
                    'result_qr' => $result_qr,
                ]);

                $tempPath = storage_path("app/temp_recibo_{$payment->payment_identifier}.pdf");
                $pdf->save($tempPath);

                $mailData = [
                    'resident' => $residentName,
                    'amount' => number_format($payment->amount, 2),
                    'service' => $payment->service ? $payment->service->name : $payment->payment_type,
                ];

                Mail::to($payment->resident->email)->send(new PaymentReceiptMail($mailData, $tempPath));

                if (file_exists($tempPath)) {
                    @unlink($tempPath);
                }
            } catch (\Exception $e) {
                Log::error('Error enviando recibo PDF por correo al residente: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado exitosamente' . ($payment->resident && $payment->resident->email ? ' y comprobante enviado por correo' : ''),
            'data' => $payment
        ], 201);
    }

    public function cancel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|exists:payments,id',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
                'message' => 'Datos requeridos no válidos para cancelar el pago'
            ], 422);
        }

        $payment = Payment::find($request->payment_id);
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'El pago no fue encontrado o ya fue cancelado'
            ], 404);
        }

        PaymentCancelled::create([
            'payment_id' => $payment->id,
            'resident_id' => $payment->resident_id,
            'payment_type' => $payment->payment_type,
            'service_id' => $payment->service_id,
            'amount' => $payment->amount,
            'payment_identifier' => $payment->payment_identifier,
            'user_id' => $request->user()->id ?? $payment->user_id,
            'reason' => $request->reason,
        ]);

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pago cancelado exitosamente'
        ]);
    }

    public function cancelledList(Request $request)
    {
        $query = PaymentCancelled::with(['resident', 'service', 'user']);

        if ($request->filled('resident_id')) {
            $query->where('resident_id', $request->resident_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_identifier', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%")
                  ->orWhereHas('resident', function($rq) use ($search) {
                      $rq->where('name', 'like', "%{$search}%")
                        ->orWhere('paternal_surname', 'like', "%{$search}%")
                        ->orWhere('maternal_surname', 'like', "%{$search}%")
                        ->orWhere('card_id', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                  });
            });
        }

        $cancelled = $query->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $cancelled
        ]);
    }

    public function getPaymentsByResident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resident_id' => 'required|exists:residents,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payments = Payment::with(['service'])
            ->where('resident_id', $request->resident_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    public function generatePdf($id)
    {
        $payment = Payment::with(['resident', 'service', 'user'])->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'El pago especificado no existe'
            ], 404);
        }

        $residentName = $payment->resident 
            ? trim("{$payment->resident->name} {$payment->resident->paternal_surname} {$payment->resident->maternal_surname}")
            : 'Residente';

        $qrText = "Folio: {$payment->id}\nIdentificador: {$payment->payment_identifier}\nResidente: {$residentName}\nMonto: \$" . number_format($payment->amount, 2);

        $qrCode = QrCode::create($qrText);
        $writer = new PngWriter();
        $result_qr = $writer->write($qrCode);

        $pdf = Pdf::loadView('pdf', [
            'payment' => $payment,
            'resident' => $residentName,
            'payment_type' => $payment->payment_type ?? 'Mantenimiento',
            'service' => $payment->service ? $payment->service->name : ($payment->payment_type ?? 'Mantenimiento'),
            'result_qr' => $result_qr,
        ]);

        return $pdf->stream("recibo-{$payment->payment_identifier}.pdf");
    }
}
