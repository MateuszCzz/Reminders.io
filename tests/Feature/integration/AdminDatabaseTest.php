<?php

namespace Tests\Feature\integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SystemEvent;
class AdminDatabaseTest extends TestCase{

    use RefreshDatabase;

    public function test_adding_system_events_to_database(): void {

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
}
