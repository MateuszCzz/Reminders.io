<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Relative;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class RelativeTest extends TestCase
{

    use RefreshDatabase;

    public function test_relatives_api_index()
    {
        $user = User::factory()->create();
        Relative::factory(5)->create();

        $response = $this
            ->actingAs($user)
            ->get('api/relatives');

        $response->assertOk();
        $response->assertJsonCount(5, 'data');

    }


    public function test_relatives_api_show()
    {
        $user = User::factory()->create();
        $relative = Relative::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("api/relatives/{$relative->id}");

        $response->assertOk();
        $response->assertJsonFragment([
            'id' => $relative->id,
        ]);
    }


    public function test_relatives_api_store()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'John',
            'birthday' => '1990-01-01',
        ];

        $response = $this->actingAs($user)->post('api/relatives', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('relatives', [
            'user_id' => $user->id,
            'name' => 'John',
            'birthday' => '1990-01-01',
        ]);
    }


    public function test_relatives_api_update()
    {
        $user = User::factory()->create();
        $relative = Relative::factory()->create(['user_id' => $user->id]);
    
        $data = [
            'name' => 'Updated Name',
            'birthday' => '1990-01-01',
        ];
    
        $response = $this->actingAs($user)->put("api/relatives/{$relative->id}", $data);
    
        $response->assertStatus(200);
    
        $this->assertDatabaseHas('relatives', [
            'id' => $relative->id,
            'user_id' => $user->id,
            'name' => 'Updated Name',
            'birthday' => '1990-01-01',
        ]);
    }    

    public function test_relatives_api_destoy()
    {
        $user = User::factory()->create();
        $relative = Relative::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("api/relatives/{$relative->id}");

        $response->assertStatus(200);
    }
}