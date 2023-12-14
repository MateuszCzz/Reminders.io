<?php

namespace Tests\Unit;

use App\Models\SystemEvent;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Relative;


class EventTest extends TestCase
{
    use RefreshDatabase;
    public function test_calculating_for_relative_name_day(): void{

        $user = User::factory()->create();

        $relativeNames = ['John', 'Jane', 'Bob']; 
        $relatives = [];

        foreach ($relativeNames as $name) {
            $relatives[] = Relative::factory()->create(['user_id' => $user->id, 'name' => $name]);
        }

        foreach ($relatives as $relative) {
            $eventType = SystemEvent::factory()->create(['type'  => 'name day', 'name' => $relative]);
        }
    }
}
