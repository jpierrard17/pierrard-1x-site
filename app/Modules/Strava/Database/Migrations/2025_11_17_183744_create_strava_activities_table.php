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
        Schema::create('strava_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->float('distance')->nullable();
            $table->integer('moving_time')->nullable();
            $table->integer('elapsed_time')->nullable();
            $table->float('total_elevation_gain')->nullable();
            $table->string('type')->nullable();
            $table->string('sport_type')->nullable();
            $table->timestamp('start_date_local')->nullable();
            $table->string('timezone')->nullable();
            $table->json('start_latlng')->nullable();
            $table->json('end_latlng')->nullable();
            $table->string('map_id')->nullable();
            $table->text('map_summary_polyline')->nullable();
            $table->text('map_polyline')->nullable();
            $table->string('gear_id')->nullable();
            $table->text('description')->nullable();
            $table->float('calories')->nullable();
            $table->float('average_speed')->nullable();
            $table->float('max_speed')->nullable();
            $table->float('average_cadence')->nullable();
            $table->float('average_watts')->nullable();
            $table->float('average_heartrate')->nullable();
            $table->float('max_heartrate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strava_activities');
    }
};
