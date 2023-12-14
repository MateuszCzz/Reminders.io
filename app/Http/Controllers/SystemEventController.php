<?php

namespace App\Http\Controllers;

use App\Models\SystemEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = SystemEvent::All();
        return response()->json(['data' => $events]);
    }

    public function getNonCustomEvents()
    {
        $nonCustomEvents = SystemEvent::where(function ($query) {
            $query->where('isCustom', false);
        })->get();
        
        return response()->json(['data' => $nonCustomEvents]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){
        $event = SystemEvent::create($request->all());
        return response()->json(['data' => $event], 201);
    }
    
    public function jsonInjection(Request $request)
    {

        $data = $request->json()->all();

        foreach ($data as $name => $events) {
            foreach ($events as $event) {

                SystemEvent::create([
                    'name' => $name,
                    'month' => date('m', strtotime($event['date'])),
                    'day' => date('d', strtotime($event['date'])),
                    'type' => $event['type'],
                ]);
            }
        }

        return response()->json(['message' => 'System events added successfully'], 200);
    }

    public function destroyAll(Request $request)
    {
        try {
            $events = SystemEvent::all();

            foreach ($events as $event) {
                $event->notifications()->delete();
                $event->delete();
            }
            
            return response()->json(['message' => 'All data removed successfully'], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Failed to remove data'.$e], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'month' => 'required|integer|between:1,12',
            'day' => 'required|integer|between:1,31',
            'type' => 'required|in:name day,birthday,holiday,anniversary,other',
            'isCustom' => 'required|boolean',
            'notification_message' => 'nullable|string',
        ]);

        $systemEvent = SystemEvent::create($request->all());

        return response()->json(['data' => $systemEvent], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $systemEvent = SystemEvent::find($id);

        if (!$systemEvent) {
            return response()->json(['error' => 'SystemEvent not found'], 404);
        }
        return response()->json(['data' => $systemEvent]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $systemEvent = SystemEvent::find($id);

        if (!$systemEvent) {
            return response()->json(['error' => 'SystemEvent not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'month' => 'required|integer|between:1,12',
            'day' => 'required|integer|between:1,31',
            'type' => 'required|in:name day,birthday,holiday,anniversary,other',
            'isCustom' => 'required|boolean',
            'notification_message' => 'nullable|string',
        ]);

        $systemEvent->update($request->all());

        return response()->json(['data' => $systemEvent], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $systemEvent = SystemEvent::find($id);

        if (!$systemEvent) {
            return response()->json(['error' => 'SystemEvent not found'], 404);
        }

        $systemEvent->notifications()->delete();

        $systemEvent->delete();

        return response()->json(['message' => 'SystemEvent deleted']);
    }
}
