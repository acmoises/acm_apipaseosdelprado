<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Resident;
use Illuminate\Http\Request;

class DepartureApiController extends Controller
{
    public function index(Request $request)
    {
        $departures = Departure::with('resident')->orderBy('id', 'desc')->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $departures
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

        $departure = Departure::create([
            'resident_id' => $resident->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Acceso de salida registrado',
            'resident' => $resident,
            'departure' => $departure
        ], 201);
    }
}
