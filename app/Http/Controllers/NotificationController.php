<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\SystemEvent;
use App\Models\Relative;
use Illuminate\Http\Request;
use DateTime;
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function refreshAllForUser(User $user)
    {
        $events = SystemEvent::where('isCustom', 'no')
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

        $relatives = Relative::where('user_id', $user->id)->get();

        foreach ($relatives as $relative) {
            $closestEvent = null;
            $closestEventDiff = 367;

            foreach ($events as $event) {
                if ($event->type == 'name day') {
                    $eventDate = $event->scheduled_at;
                    $nextBirthday = $relative->birthday->copy()->addDay();
                    $eventDiff = $eventDate->diffInDays($nextBirthday, false);

                    if ($eventDiff >= 0 && $eventDiff < $closestEventDiff) {
                        $closestEvent = $event;
                        $closestEventDiff = $eventDiff;
                    }
                } else {
                    $this->refreshEventNotifications($event,$user);
                }
            }

            if ($closestEvent) {
                $this->refreshEventNotifications($closestEvent,$user);
            }
        }
    }
    private function refreshEventNotifications($event, $user)
    {
        $eventDay = $event->day;
        $eventMonth = $event->month;
        $currentYear = date('Y');

        $notificationDate = new DateTime("$currentYear-$eventMonth-$eventDay");
    
        // Check if the notification date is in the past
        if ($notificationDate < new DateTime()) {
            $notificationDate->modify('+1 year');
        }

        $notification = $event->notifications()
            ->where('user_id', $user->id)
            ->where('scheduled_at', $notificationDate)
            ->first();
    
        if (!$notification) {
            $notification = new Notification();
            $notification->event_id = $event->id;
            $notification->user_id = $user->id;
            $notification->scheduled_at = $notificationDate;
            // Set other properties as needed
            $notification->save();
        } else {
            // Update the existing notification if needed
        }
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
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
