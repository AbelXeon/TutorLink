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
           Schema::create('tutor_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->text('bio');
        $table->integer('experience_years');
        $table->string('availability_status');
        $table->string('qualification');
        $table->integer('max_students');
        $table->decimal('price_per_hour', 10, 2);
        $table->string('grade_level');
        $table->integer('total_reviews')->default(0);
        $table->string('teaching_mode');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
