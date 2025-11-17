<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HevyWorkout extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'notes',
        'start_time',
        'end_time',
    ];

    public function exercises()
    {
        return $this->hasMany(HevyWorkoutExercise::class);
    }
}
