<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Database\Seeder;

class UserInvitationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function ($user) {
            if ($user->email !== 'admin@domain.com') {
                UserInvitation::create([
                    'invite_user_id' => rand(1, 10),
                    'invitee_user_id' => $user->id,
                    'is_successful' => rand(0, 1),
                    'invitation_date' => now(),
                ]);
            }
        });
    }
}
