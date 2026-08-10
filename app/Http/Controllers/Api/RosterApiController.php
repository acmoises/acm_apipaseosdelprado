<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RosterApiController extends Controller
{
    public function index(Request $request)
    {
        $rosters = Roster::orderBy('id', 'desc')->paginate($request->get('per_page', 15));

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
}
