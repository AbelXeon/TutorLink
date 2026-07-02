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
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')
          ->constrained('users')
          ->cascadeOnDelete();
    $table->string('email');
    $table->string('code');
    $table->unsignedTinyInteger('attempt_count')->default(0);
    $table->boolean('is_used')->default(false);
    $table->string('purpose');
    $table->timestamp('expires_at');
    $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email__verifications');
    }
};
