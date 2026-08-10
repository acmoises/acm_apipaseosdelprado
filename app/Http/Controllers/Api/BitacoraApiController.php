<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BitacoraApiController extends Controller
{
    public function index(Request $request)
    {
        $bitacoras = Bitacora::orderBy('id', 'desc')->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $bitacoras
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nota' => 'required|string',
            'estatus' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $bitacora = Bitacora::create([
            'nota' => $request->nota,
            'estatus' => $request->estatus,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Entrada en bitácora creada exitosamente',
            'data' => $bitacora
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $bitacora = Bitacora::find($id);

        if (!$bitacora) {
            return response()->json([
                'success' => false,
                'message' => 'Registro no encontrado'
            ], 404);
        }

        $bitacora->update($request->only(['nota', 'estatus']));

        return response()->json([
            'success' => true,
            'message' => 'Bitácora actualizada correctamente',
            'data' => $bitacora
        ]);
    }

    public function destroy($id)
    {
        $bitacora = Bitacora::find($id);

        if (!$bitacora) {
            return response()->json([
                'success' => false,
                'message' => 'Registro no encontrado'
            ], 404);
        }

        $bitacora->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro de bitácora eliminado'
        ]);
    }
}
