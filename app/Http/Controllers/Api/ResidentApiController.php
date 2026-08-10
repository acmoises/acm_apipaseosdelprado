<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResidentApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Resident::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            
            // If it's a number and an exact ID match exists, ONLY return that resident
            if (is_numeric($search) && Resident::where('id', $search)->exists()) {
                $query->where('id', $search);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('paternal_surname', 'like', "%{$search}%")
                      ->orWhere('maternal_surname', 'like', "%{$search}%")
                      ->orWhere('card_id', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }
        }

        $residents = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $residents
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'card_id' => 'required|string|max:100|unique:residents,card_id',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $resident = Resident::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Residente creado correctamente',
            'data' => $resident
        ], 201);
    }

    public function show($id)
    {
        $resident = Resident::find($id);

        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'Residente no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resident
        ]);
    }

    public function getByCardId($card_id)
    {
        $resident = Resident::where('card_id', $card_id)->first();

        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró residente con la tarjeta/código indicado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resident
        ]);
    }

    public function update(Request $request, $id)
    {
        $resident = Resident::find($id);

        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'Residente no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'paternal_surname' => 'sometimes|required|string|max:255',
            'maternal_surname' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'address' => 'sometimes|required|string|max:255',
            'card_id' => 'sometimes|required|string|max:100|unique:residents,card_id,' . $id,
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $resident->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Residente actualizado correctamente',
            'data' => $resident
        ]);
    }

    public function destroy($id)
    {
        $resident = Resident::find($id);

        if (!$resident) {
            return response()->json([
                'success' => false,
                'message' => 'Residente no encontrado'
            ], 404);
        }

        $resident->delete();

        return response()->json([
            'success' => true,
            'message' => 'Residente eliminado correctamente'
        ]);
    }
}
