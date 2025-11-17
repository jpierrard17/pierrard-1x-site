<?php

namespace App\Modules\Hevy\Models;

use Illuminate\Database\Eloquent\Model;

class HevyRoutineFolder extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function routines()
    {
        return $this->hasMany(HevyRoutine::class, 'hevy_routine_folder_id');
    }
}
