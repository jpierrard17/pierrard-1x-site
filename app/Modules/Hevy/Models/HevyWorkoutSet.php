<?php

namespace App\Modules\Hevy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HevyWorkoutSet extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'hevy_workout_exercise_id',
        'index',
        'set_type',
        'weight_kg',
        'reps',
        'distance_meters',
        'duration_seconds',
        'rpe',
        'is_dropset',
        'is_failed',
        'notes',
    ];

    public function exercise()
    {
        return $this->belongsTo(HevyWorkoutExercise::class, 'hevy_workout_exercise_id');
    }
}
