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
        $query = Spent::with('user');

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year);
        }

        $spents = $query->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 50));

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
            'evidence' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('evidences'), $filename);
            $evidencePath = $filename;
        }

        $spent = Spent::create([
            'user_id' => $request->user()->id ?? 1,
            'concept' => $request->concept,
            'amount' => $request->amount,
            'evidence' => $evidencePath ?? '',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gasto registrado correctamente',
            'data' => $spent
        ], 201);
    }

    public function cancel($id)
    {
        $spent = Spent::find($id);
        
        if (!$spent) {
            return response()->json([
                'success' => false,
                'message' => 'El gasto no fue encontrado'
            ], 404);
        }

        $spent->status = 'cancelled';
        $spent->save();

        return response()->json([
            'success' => true,
            'message' => 'Gasto cancelado correctamente',
            'data' => $spent
        ]);
    }
}
