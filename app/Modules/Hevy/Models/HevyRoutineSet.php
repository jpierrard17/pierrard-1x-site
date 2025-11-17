<?php

namespace App\Modules\Hevy\Models;

use Illuminate\Database\Eloquent\Model;

class HevyRoutineSet extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'hevy_routine_exercise_id',
        'reps',
        'weight_kg',
        'is_warmup',
        'notes',
    ];

    public function routineExercise()
    {
        return $this->belongsTo(HevyRoutineExercise::class, 'hevy_routine_exercise_id');
    }
}
