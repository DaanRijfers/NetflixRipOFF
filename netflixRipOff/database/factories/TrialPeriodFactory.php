<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TrialPeriodFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'start_date' => $this->faker->dateTimeBetween('-30 days', '-10 days'),
            'end_date' => $this->faker->dateTimeBetween('-9 days', 'now'),
        ];
    }
}
