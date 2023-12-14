<?php

namespace Tests\Feature\integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SystemEvent;
use App\Models\Notification;

class AdminDatabaseTest extends TestCase{

    use RefreshDatabase;

    public function test_api_injecting_system_events_into_database(): void {

        $data = [
            "Aaron's name day" => [
                [
                    "date" => "01.07",
                    "type" => "name day"
                ],
                [
                    "date" => "09.10",
                    "type" => "name day"
                ]
            ],
            "New Year" => [
                [
                    "date" => "01.01",
                    "type" => "holiday"
                ]
            ],
        ];

        $adminUser = User::factory()->isAdmin()->create();
        $response = $this->postJson('/api/create-token', [
            'email' => $adminUser->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response->json('token')])->postJson('api/inject-system-events', $data);
        
        $response->assertStatus(200);

        foreach ($data as $name => $events) {
            foreach ($events as $event) {
                $this->assertDatabaseHas('system_events', [
                    'name' => $name,
                    'month' => date('m', strtotime($event['date'])),
                    'day' => date('d', strtotime($event['date'])),
                    'type' => $event['type'],
                ]);
            }
        }
    }

    public function test_api_removing_all_system_events(): void{

        $systemEvents = SystemEvent::factory(5)->create();

        foreach ($systemEvents as $event) {
            Notification::factory(3)->create(['event_id' => $event->id]);
        }

        $adminUser = User::factory()->isAdmin()->create();
        $response = $this->postJson('/api/create-token', [
            'email' => $adminUser->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $response->json('token')])->postJson('api/remove-system-events');
        $response->assertStatus(200);

        $this->assertDatabaseCount('system_events', 0);
        $this->assertDatabaseCount('notifications', 0);

    }
}

