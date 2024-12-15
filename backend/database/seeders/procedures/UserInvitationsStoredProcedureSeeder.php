<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserInvitationsStoredProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adds a new invitation
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE AddInvitation(
                IN InviteUserId bigint(20),
                IN InviteeUserId bigint(20)
            )
            BEGIN
                INSERT INTO user_invitations(invite_user_id, invitee_user_id)
                VALUES (InviteUserId, InviteeUserId);
            END;
        ");

        // Marks invitation as completed
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE MarkInvitationAsCompleted(
                IN InviteUserId bigint(20),
                IN InviteeUserId bigint(20)
            )
            BEGIN
                UPDATE user_invitations
                SET is_successful = 1
                WHERE invite_user_id = InviteUserId AND invitee_user_id = InviteeUserId;
            END;
        ");
    }
}
