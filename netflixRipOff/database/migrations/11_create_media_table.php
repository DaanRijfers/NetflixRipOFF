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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('duration')->nullable();
            $table->enum('media_type', ['movie', 'series']);
            $table->string('series_title')->nullable();
            $table->integer('season_number')->nullable();
            $table->integer('episode_number')->nullable();
            $table->foreignId('language_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });                   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
