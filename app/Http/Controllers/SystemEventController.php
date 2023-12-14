<?php

namespace App\Http\Controllers;

use App\Models\SystemEvent;
use Illuminate\Http\Request;

class SystemEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request){

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


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemEvent $systemEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemEvent $systemEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemEvent $systemEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemEvent $systemEvent)
    {
        //
    }
}
