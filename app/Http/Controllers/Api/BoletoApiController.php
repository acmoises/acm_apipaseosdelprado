<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoletoApiController extends Controller
{
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folio_inicial' => 'required|integer|min:1',
            'folio_final' => 'required|integer|gte:folio_inicial',
            'fecha' => 'required|date',
            'color' => 'required|string',
            'figura' => 'required|string|in:circulo,cuadrado,triangulo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $boletos = [];
        for ($i = (int)$request->folio_inicial; $i <= (int)$request->folio_final; $i++) {
            $boletos[] = [
                'folio' => $i,
                'fecha' => $request->fecha,
                'color' => $request->color,
                'figura' => $request->figura,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $boletos,
            'total' => count($boletos)
        ]);
    }

    public function generatePdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folio_inicial' => 'required|integer|min:1',
            'folio_final' => 'required|integer|gte:folio_inicial',
            'fecha' => 'required|date',
            'color' => 'required|string',
            'figura' => 'required|string|in:circulo,cuadrado,triangulo',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $boletos = [];
        for ($i = (int)$request->folio_inicial; $i <= (int)$request->folio_final; $i++) {
            $boletos[] = [
                'folio' => $i,
                'fecha' => $request->fecha,
                'color' => $request->color,
                'figura' => $request->figura,
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf-boletos', [
            'boletos' => $boletos
        ])->setPaper('letter', 'portrait');

        return $pdf->stream("boletos.pdf");
    }
}
