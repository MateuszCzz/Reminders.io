<?php

namespace Database\Factories;

use App\Models\SystemEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SystemEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $eventTypes = ['name day', 'birthday', 'holiday', 'anniversary', 'other'];
        $hasUser = $this->faker->boolean(33);
        
        return [
            'user_id' => $hasUser ? User::factory()->create()->id : null,
            'name' => $this->faker->name,
            'month' => $this->faker->numberBetween(1, 12),
            'day' => $this->faker->numberBetween(1, 31),
            'type' => $this->faker->randomElement($eventTypes),
            'isCustom' => $hasUser,
            'notification_message' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
