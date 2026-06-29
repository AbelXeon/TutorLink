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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
          ->constrained('users')
          ->cascadeOnDelete();
    $table->string('ip_address', 45);
    $table->string('device');
    $table->string('session_token')->unique();
    $table->timestamp('expired_at');
    $table->timestamp('logged_out_at')->nullable();
    $table->timestamp('last_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user__sessions');
    }
};
