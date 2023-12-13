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

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }
}