<?php

namespace Database\Factories;

use App\Models\SystemEvent;
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
        return [
            'name' => $this->faker->word,
            'month' => $this->faker->numberBetween(1, 12),
            'day' => $this->faker->numberBetween(1, 31),
            'type' => $this->faker->randomElement(['name day', 'birthday', 'holiday', 'other']),
            'interval' => $this->faker->randomElement(['yearly', 'monthly', 'weekly', 'daily', 'other']),
        ];
    }
}
