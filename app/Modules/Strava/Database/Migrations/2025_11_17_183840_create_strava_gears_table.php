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
        Schema::create('strava_gears', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->float('distance')->default(0);
            $table->string('brand_name')->nullable();
            $table->string('model_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strava_gears');
    }
};
