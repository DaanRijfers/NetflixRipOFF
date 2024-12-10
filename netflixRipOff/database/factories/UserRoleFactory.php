<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'role_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}

