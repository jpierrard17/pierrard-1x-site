<?php

namespace App\Modules\Hevy\Models;

use Illuminate\Database\Eloquent\Model;

class HevyRoutineExercise extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'hevy_routine_id',
        'exercise_template_id',
    ];

    public function routine()
    {
        return $this->belongsTo(HevyRoutine::class, 'hevy_routine_id');
    }

    public function exerciseTemplate()
    {
        return $this->belongsTo(HevyExerciseTemplate::class, 'exercise_template_id');
    }

    public function sets()
    {
        return $this->hasMany(HevyRoutineSet::class);
    }
}
