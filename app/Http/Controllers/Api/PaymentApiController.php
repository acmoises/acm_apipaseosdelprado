<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentCancelled;
use App\Models\Resident;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['resident', 'service', 'user'])
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 15));

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

        $paymentIdentifier = 'PAY-' . strtoupper(uniqid());

        $payment = Payment::create([
            'resident_id' => $request->resident_id,
            'payment_type' => $request->payment_type,
            'service_id' => $request->service_id,
            'amount' => $request->amount,
            'payment_identifier' => $paymentIdentifier,
            'user_id' => $request->user()->id ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pago registrado exitosamente',
            'data' => $payment->load(['resident', 'service'])
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
                'errors' => $validator->errors()
            ], 422);
        }

        $payment = Payment::find($request->payment_id);

        PaymentCancelled::create([
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
        $cancelled = PaymentCancelled::with(['resident', 'service', 'user'])
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 15));

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
}
