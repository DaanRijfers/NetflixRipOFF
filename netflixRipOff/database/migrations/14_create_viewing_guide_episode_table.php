<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('viewing_guide_episode', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viewing_guide_id')->constrained()->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes')->onDelete('cascade');
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viewing_guide_episode');
    }
};
