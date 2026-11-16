<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            ->get()
            ->map(function ($payment) {
                $payment->folio = sprintf('SPP-%03d', $payment->id);
                $payment->public_pdf_url = url("/api/system-payments/{$payment->id}/receipt?sig=" . $this->makeReceiptSignature($payment));
                return $payment;
            });

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
            'client_billing' => [
                'name' => config('app.client_billing_name', 'OSVALDO CASTAÑEDA DIAZ'),
                'phone' => preg_replace('/[^0-9]/', '', (string) config('app.client_billing_phone', '5577499651')),
            ],
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

    /**
     * Genera la firma HMAC para descarga pública/verificación del comprobante PDF.
     */
    protected function makeReceiptSignature(SystemPayment $payment): string
    {
        return substr(hash_hmac('sha256', "syspay:{$payment->id}:{$payment->month}:{$payment->year}", config('app.key')), 0, 24);
    }

    /**
     * Genera el comprobante PDF (Nota de Remisión SoftMinds) autenticado para el SuperAdmin.
     */
    public function generatePdf(Request $request, $id)
    {
        $this->checkSuperAdmin($request);

        $payment = SystemPayment::find($id);
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'El registro de pago de licencia no existe.',
            ], 404);
        }

        return $this->buildPdfResponse($payment);
    }

    /**
     * Permite abrir/descargar el comprobante PDF desde el enlace enviado por WhatsApp mediante firma HMAC.
     */
    public function publicReceipt(Request $request, $id)
    {
        $payment = SystemPayment::find($id);
        if (!$payment) {
            abort(404, 'Comprobante no encontrado.');
        }

        $expectedSig = $this->makeReceiptSignature($payment);
        $providedSig = (string) $request->query('sig', '');

        if (!hash_equals($expectedSig, $providedSig)) {
            abort(403, 'Enlace de comprobante inválido o expirado.');
        }

        return $this->buildPdfResponse($payment);
    }

    /**
     * Construye el PDF profesional de Nota de Remisión de SoftMinds.
     */
    protected function buildPdfResponse(SystemPayment $payment)
    {
        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $folio = sprintf('SPP-%03d', $payment->id);
        $paidDate = $payment->paid_at ? Carbon::parse($payment->paid_at) : Carbon::parse($payment->created_at);
        $monthName = $monthNames[$payment->month] ?? "Mes {$payment->month}";
        $periodLabel = mb_strtoupper("{$monthName} {$payment->year}");
        $amount = $payment->amount !== null ? (float) $payment->amount : 4000.00;
        $formattedAmount = number_format($amount, 2);

        $clientName = config('app.client_billing_name', 'OSVALDO CASTAÑEDA DIAZ');
        $rawPhone = preg_replace('/[^0-9]/', '', (string) config('app.client_billing_phone', '5577499651'));
        $clientPhone = strlen($rawPhone) === 10
            ? substr($rawPhone, 0, 2) . ' ' . substr($rawPhone, 2, 4) . ' ' . substr($rawPhone, 6, 4)
            : (string) config('app.client_billing_phone', '55 7749 9651');
        $referenceCode = sprintf('L%02d-S-V1', $payment->id);

        $securityHash = strtoupper(substr(hash('sha256', $payment->id . $folio . $amount . config('app.key')), 0, 16));

        $qrText = "SOFTMINDS - NOTA DE REMISION\n"
            . "Folio: {$folio}\n"
            . "Cliente: {$clientName}\n"
            . "Concepto: Licencia Paseos del Prado ({$monthName} {$payment->year})\n"
            . "Total: \${$formattedAmount} MXN\n"
            . "Sello: {$securityHash}";

        $qrCode = QrCode::create($qrText);
        $writer = new PngWriter();
        $resultQr = $writer->write($qrCode);

        // Logotipo vectorial profesional de SoftMinds (Hexágono Tecnológico + Monograma SM)
        $svgLogo = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="120" height="120">
  <!-- Fondo Hexagonal Ejecutivo -->
  <polygon points="60,6 108,33 108,87 60,114 12,87 12,33" fill="#0f172a" stroke="#2563eb" stroke-width="4"/>
  <!-- Acento Geométrico Interno -->
  <polygon points="60,15 99,37 99,83 60,105 21,83 21,37" fill="#1e3a8a" opacity="0.35"/>
  <!-- Rombo Superior Tecnológico -->
  <polygon points="60,14 74,22 60,30 46,22" fill="#38bdf8"/>
  <!-- Monograma SM en trazos limpios -->
  <!-- Letra S -->
  <path d="M 52 44 L 33 44 C 29 44 27 46 27 50 L 27 56 C 27 59 29 61 33 61 L 46 61 C 50 61 52 63 52 67 L 52 74 C 52 78 50 80 46 80 L 26 80" fill="none" stroke="#ffffff" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
  <!-- Letra M -->
  <path d="M 63 80 L 63 44 L 78 64 L 93 44 L 93 80" fill="none" stroke="#38bdf8" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
  <!-- Línea inferior de acento -->
  <line x1="38" y1="92" x2="82" y2="92" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>
</svg>
SVG;

        $logoDataUri = 'data:image/svg+xml;base64,' . base64_encode($svgLogo);

        $pdf = Pdf::loadView('pdf-system-payment', [
            'payment' => $payment,
            'folio' => $folio,
            'day' => $paidDate->format('d'),
            'month_num' => $paidDate->format('m'),
            'year_num' => $paidDate->format('Y'),
            'client_name' => $clientName,
            'client_phone' => $clientPhone,
            'reference_code' => $referenceCode,
            'period_label' => $periodLabel,
            'payment_notes' => $payment->notes,
            'formatted_amount' => $formattedAmount,
            'qr_data_uri' => $resultQr->getDataUri(),
            'logo_data_uri' => $logoDataUri,
            'security_hash' => $securityHash,
        ]);

        return $pdf->stream("Nota_SoftMinds_{$folio}_{$monthName}_{$payment->year}.pdf");
    }
}
