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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('payment_method')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->timestamps();
        });                    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
