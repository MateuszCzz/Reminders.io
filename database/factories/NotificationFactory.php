<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SystemEvent;
use App\Models\Notification;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'message' => $this->faker->sentence,
            'event_id' => SystemEvent::factory()->create()->id,
            'wasShowed' => $this->faker->boolean,
            'wasClosed' => $this->faker->boolean,
            'notification_date' => $this->faker->dateTime,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
