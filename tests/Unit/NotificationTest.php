<?php

namespace Tests\Unit;

use App\Models\SystemEvent;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Relative;
use App\Models\Notification;
use Carbon\Carbon;
class NotificationTest extends TestCase
{
    use RefreshDatabase;
    public function test_find_name_day_for_relative()
    {
        $user = User::factory()->create();

        $relative = Relative::create([
            'name' => 'John Doe',
            'birthday' => '2000-02-02',
            'user_id' => $user->id,
        ]);

        SystemEvent::factory()->create([
            'month' => 1,
            'day' => 1,
            'type' => 'name day',
            'name' => 'John Doe',
            'user_id' => $user->id,
        ]);

        SystemEvent::factory()->create([
            'month' => 2,
            'day' => 1,
            'type' => 'name day',
            'name' => 'John Doe',
            'user_id' => $user->id,
        ]);
        SystemEvent::factory()->create([
            'month' => 2,
            'day' => 15,
            'type' => 'name day',
            'name' => 'John Doe',
            'user_id' => $user->id,
        ]);
        $nameDayEvents = SystemEvent::factory()->create([
            'month' => 2,
            'day' => 10,
            'type' => 'name day',
            'name' => 'John Doe',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->put("api/nameDayForRelative/{$relative->id}/{$user->id}");
        $response->assertStatus(200);
        $notification = $response->json('data');
        $this->assertEquals($nameDayEvents->id, $notification['original']['data']['event_id']);
    }

    public function test_create_find_notification()
    {
        $user = User::factory()->create();
        $event = SystemEvent::factory()->create([
            'name' => 'New Year',
            'month' => 1,
            'day' => 1,
            'type' => 'holiday',
            'isCustom' => 0,
            'notification_message' => 'New year is coming soon',
        ]);

        $notificationDate = now()->addDay();
        $notificationDate = Carbon::parse($notificationDate)->toDateString();
        $this->actingAs($user)->postJson("api/notificationCreateFind/{$user->id}/{$event->id}/{$notificationDate}", [
        ])->assertStatus(201);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'notification_date' => Carbon::parse($notificationDate)->toDateString(),
            'wasShowed' => false,
            'wasClosed' => false,
        ]);

        // Attempt to create the same notification again
        $this->actingAs($user)->postJson("api/notificationCreateFind/{$user->id}/{$event->id}/{$notificationDate}", [
        ])->assertStatus(200);
    }
    public function test_create_notifications_for_user()
    {
        $user = User::factory()->create();
        //user event
        SystemEvent::factory()->create([
            'user_id' => $user->id,
            'isCustom' => 1,
            'type' => 'name day',
            'name' => 'test1',
            'notification_message' => 'Custom event message',
        ]);
        //system event
        SystemEvent::factory()->create([
            'isCustom' => 0,
            'type' => 'name day',
            'name' => 'test2',
            'user_id' => $user->id,
            'notification_message' => 'Non-custom event message',
        ]);
        //other user event
        SystemEvent::factory()->create([
            'isCustom' => 1,
            'type' => 'name day',
            'name' => 'test3',
            'user_id' => User::factory()->create()->id,
            'notification_message' => 'Non-custom event message',
        ]);
        $this->actingAs($user)->postJson('api/createNotificationsForUser/' . $user->id);

        // Assert that the custom event creates notifications for 6 years (current year + 5 future years) x2
        $this->assertDatabaseCount('notifications', 12);
    }
}
