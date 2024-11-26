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
        Schema::create('profile_watchtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->nullable()->constrained('movies')->onDelete('set null');
            $table->foreignId('episode_id')->nullable()->constrained('episodes')->onDelete('set null');
            $table->integer('watch_time'); // watch time in minutes
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_watchtimes');
    }
};
