<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserInvitationFactory extends Factory
{
    public function definition()
    {
        return [
            'invite_user_id' => $this->faker->numberBetween(1, 10),
            'invitee_user_id' => $this->faker->optional()->numberBetween(1, 10),
            'is_successful' => $this->faker->boolean(),
            'invitation_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}

