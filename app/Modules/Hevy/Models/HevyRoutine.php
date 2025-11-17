<?php

namespace App\Modules\Hevy\Models;

use Illuminate\Database\Eloquent\Model;

class HevyRoutine extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'notes',
        'hevy_routine_folder_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercises()
    {
        return $this->hasMany(HevyRoutineExercise::class);
    }

    public function folder()
    {
        return $this->belongsTo(HevyRoutineFolder::class, 'hevy_routine_folder_id');
    }
}
