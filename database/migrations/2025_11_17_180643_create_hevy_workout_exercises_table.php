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
        Schema::create('hevy_workout_exercises', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('hevy_workout_id');
            $table->string('exercise_template_id');
            $table->timestamps();

            $table->foreign('hevy_workout_id')->references('id')->on('hevy_workouts')->onDelete('cascade');
            $table->foreign('exercise_template_id')->references('id')->on('hevy_exercise_templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hevy_workout_exercises');
    }
};
