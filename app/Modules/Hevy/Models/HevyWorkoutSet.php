<?php

namespace App\Modules\Hevy\Models;

use Illuminate\Database\Eloquent\Model;

class HevyWorkoutSet extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'hevy_workout_exercise_id',
        'reps',
        'weight_kg',
        'distance_km',
        'duration_seconds',
        'is_warmup',
        'is_dropset',
        'is_failed',
        'notes',
    ];

    public function exercise()
    {
        return $this->belongsTo(HevyWorkoutExercise::class, 'hevy_workout_exercise_id');
    }
}
