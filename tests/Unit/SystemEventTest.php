<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SystemEvent;
use App\Models\User;

class SystemEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_system_events_api_index()
    {
        $user = User::factory()->create();
        SystemEvent::factory(5)->create();

        $response = $this
            ->actingAs($user)
            ->get('api/ system-events/');

        $response->assertOk();
        $response->assertJsonCount(5, 'data');
    }

    public function test_system_events_api_show()
    {
        $user = User::factory()->create();
        $event = SystemEvent::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("api/system-events/{$event->id}");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $event->id,
        ]);
    }

    public function test_system_events_api_store()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'New Year',
            'month' => 1,
            'day' => 1,
            'type' => 'holiday',
            'isCustom' => 0,
            'notification_message' => 'New year is comming soon',
        ];

        $response = $this->actingAs($user)->post('api/system-events', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('system_events', [
            'name' => 'New Year',
            'month' => 1,
            'day' => 1,
            'type' => 'holiday',
            'isCustom' => 0,
        ]);
    }

    public function test_system_events_api_update()
    {
        $user = User::factory()->create();
        $event = SystemEvent::factory()->create(['user_id' => $user->id]);

        $data = [
            'name' => 'New Year',
            'month' => 1,
            'day' => 1,
            'type' => 'holiday',
            'isCustom' => true,
            'notification_message' => 'New year is comming soon',
        ];
        $response = $this->actingAs($user)->put("api/system-events/{$event->id}", $data);
        $response->assertStatus(200);
    
        $this->assertDatabaseHas('system_events', [
            'name' => 'New Year',
            'month' => 1,
            'day' => 1,
            'type' => 'holiday',
            'isCustom' => true,
        ]);
    }

    public function test_system_events_api_destroy()
    {
        $user = User::factory()->create();
        $event = SystemEvent::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("api/system-events/{$event->id}");

        $response->assertStatus(200);
    }
    public function test_system_events_api_non_costum_index(){
        $user = User::factory()->create();
       SystemEvent::factory(3)->non_custom()->create();
        SystemEvent::factory(5)->custom()->create();

        $response = $this
            ->actingAs($user)
            ->get('api/system-events/non-custom');

            $response->assertOk();
            
            $response->assertJsonCount(3,'data');
    }
}