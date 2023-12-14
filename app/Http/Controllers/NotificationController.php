<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\SystemEvent;
use App\Models\Relative;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function nameDayForRelative($relativeId, $userId)
    {
        $relative = Relative::find($relativeId);


        if (!$relative || !$userId || $relative->user_id != $userId) {
            return response()->json(['error' => 'Relative or User not found'], 404);
        }

        $events = SystemEvent::where('isCustom', false)
            ->orWhere(function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('type', 'name day')
            ->get();

        $closestEvent = null;
        $closestDiff = PHP_INT_MAX;
        $birthday = Carbon::parse($relative->birthday);
        $birthday->year(now()->year)->subYear();
        $notificationDate = null;
        foreach ($events as $event) {
            $eventDay = $event->day;
            $eventMonth = $event->month;
            $eventDate = Carbon::createFromDate(null, $eventMonth, $eventDay);
            $eventDate->addYear();
            $dayDifference = ($birthday->diffInDays(Carbon::parse($eventDate), false) % 365);

            if ($dayDifference >= 0 && $dayDifference < $closestDiff) {
                $closestDiff = $dayDifference;
                $closestEvent = $event;
                $notificationDate = $eventDate->subYear();
            }
        }

        if ($closestEvent == null) {
            $closestDiff = PHP_INT_MAX;

            foreach ($events as $event) {
                $eventDay = $event->day;
                $eventMonth = $event->month;
                $eventDate = Carbon::createFromDate(null, $eventMonth, $eventDay);
                $eventDate->addYear();
                $dayDifference = $birthday->diffInDays($eventDate, false);
                if ($dayDifference)
                    if ($dayDifference >= 0 && $dayDifference < $closestDiff && $dayDifference) {
                        $closestDiff = $dayDifference;
                        $closestEvent = $event;
                    }
            }
        }

        if ($closestEvent == null) {
            // Bad response if no event is found
            return response()->json(['error' => 'No name day event found'], 404);
        }

        // Create or update notification
        $notification = NotificationController::createOrFindNotification($userId,$closestEvent->id,$eventDate);

        return response()->json(['data' => $notification], 200);
    }

    public function createOrFindNotification($user_id,$event_id,$notification_date)
    {
        $existingNotification = Notification::where('user_id', $user_id)
            ->where('event_id', $event_id)
            ->where('notification_date', $notification_date)
            ->first();

        if ($existingNotification) {
            // Notification already exists
            return response()->json(['message' => 'Notification already exists'], 200);
        }

        $notification = Notification::create([
            'message' => 'Your notification message here',
            'event_id' => $event_id,
            'user_id' => $user_id,
            'notification_date' => $notification_date,
            'wasShowed' => false,
            'wasClosed' => false,
        ]);

        return response()->json(['data' => $notification], 201);
    }

    public function refreshAllForUser(User $user)
    {
        $events = SystemEvent::where(function ($query) use ($user) {
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
                    $this->refreshEventNotifications($event, $user);
                }
            }

            if ($closestEvent) {
                $this->refreshEventNotifications($closestEvent, $user);
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
