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
        Schema::create('hevy_routine_sets', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('hevy_routine_exercise_id');
            $table->integer('reps');
            $table->decimal('weight_kg', 8, 2);
            $table->boolean('is_warmup')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('hevy_routine_exercise_id')->references('id')->on('hevy_routine_exercises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hevy_routine_sets');
    }
};
