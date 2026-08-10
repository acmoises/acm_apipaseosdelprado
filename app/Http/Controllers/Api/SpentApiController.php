<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Spent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpentApiController extends Controller
{
    public function index(Request $request)
    {
        $spents = Spent::with('user')
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $spents
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'concept' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'evidence' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $spent = Spent::create([
            'user_id' => $request->user()->id ?? 1,
            'concept' => $request->concept,
            'amount' => $request->amount,
            'evidence' => $request->evidence ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gasto registrado correctamente',
            'data' => $spent
        ], 201);
    }
}
