<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relative;

class RelativeController extends Controller
{
    public function index()
    {
        $relatives = Relative::All();
        return response()->json(['data' => $relatives]);
    }

    public function show($id)
    {
        $relative = Relative::find($id);

        if (!$relative) {
            return response()->json(['error' => 'Relative not found'], 404);
        }

        if (!$this->checkOwnership($relative)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => $relative]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'second_name' => 'nullable|string',
            'surname' => 'nullable|string',
            'birthday' => 'required|date',
        ]);

        $relative = auth()->user()->relatives()->create($request->all());

        return response()->json(['data' => $relative], 201);
    }

    public function update(Request $request, $id)
    {
        $relative = Relative::find($id);

        if (!$relative) {
            return response()->json(['error' => 'Relative not found'], 404);
        }

        if (!$this->checkOwnership($relative)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string',
            'second_name' => 'nullable|string',
            'surname' => 'nullable|string',
            'birthday' => 'required|date',
        ]);

        $relative->update($request->all());

        return response()->json(['data' => $relative], 200);
    }

    public function destroy($id)
    {
        $relative = Relative::find($id);

        if (!$relative) {
            return response()->json(['error' => 'Relative not found'], 404);
        }

        if (!$this->checkOwnership($relative)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $relative->delete();

        return response()->json(['message' => 'Relative deleted']);
    }

    private function checkOwnership(Relative $relative)
    {
        return auth()->user()->id === $relative->user_id; 
    }
}
