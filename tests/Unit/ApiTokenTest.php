<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;


class ApiTokenTest extends TestCase{

    use RefreshDatabase;

    public function test_creating_new_token(): void{

        $user = User::factory()->create();

        //$adminUser = User::factory()->isAdmin()->create();

        $response = $this->postJson('/api/create-token', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);

    }
    public function test_creating_new_admin_token(): void{

        $adminUser = User::factory()->isAdmin()->create();

        $response = $this->postJson('/api/create-token', [
            'email' => $adminUser->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        
        $token = $response->json('token');
        $abilities = \Laravel\Sanctum\PersonalAccessToken::findToken($token)->abilities;
        
        $this->assertTrue(in_array('admin', $abilities));

    }
}