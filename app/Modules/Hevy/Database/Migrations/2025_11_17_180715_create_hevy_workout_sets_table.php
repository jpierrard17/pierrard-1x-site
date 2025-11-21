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
        Schema::create('hevy_workout_sets', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('hevy_workout_exercise_id');
            $table->integer('index');
            $table->string('set_type')->default('normal');
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->integer('reps')->nullable();
            $table->decimal('distance_meters', 8, 2)->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->integer('rpe')->nullable();
            $table->boolean('is_dropset')->default(false);
            $table->boolean('is_failed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('hevy_workout_exercise_id')->references('id')->on('hevy_workout_exercises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hevy_workout_sets');
    }
};
