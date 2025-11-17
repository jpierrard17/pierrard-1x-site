<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HevyWorkoutExercise extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'hevy_workout_id',
        'exercise_template_id',
    ];

    public function workout()
    {
        return $this->belongsTo(HevyWorkout::class, 'hevy_workout_id');
    }

    public function exerciseTemplate()
    {
        return $this->belongsTo(HevyExerciseTemplate::class, 'exercise_template_id');
    }

    public function sets()
    {
        return $this->hasMany(HevyWorkoutSet::class);
    }
}
