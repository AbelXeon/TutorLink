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
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
    $table->date('session_date');
    $table->time('start_time');
    $table->time('end_time');
    $table->string('location')->nullable();
    $table->text('note')->nullable();
    $table->string('status')->default('pending');
    $table->timestamp('accepted_at')->nullable();
    $table->timestamp('rejected_at')->nullable(); 
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
