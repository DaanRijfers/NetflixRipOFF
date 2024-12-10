<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invite_user_id')->constrained('users')->onDelete('cascade'); // The user sending the invite
            $table->foreignId('invitee_user_id')->nullable()->constrained('users')->onDelete('set null'); // The invited user
            $table->boolean('is_successful')->default(false);
            $table->timestamp('invitation_date')->nullable();
            $table->timestamps();
        });                   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_invitations');
    }
};
