<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HevyExerciseTemplate extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'type',
        'primary_muscles',
        'secondary_muscles',
        'equipment',
    ];

    protected $casts = [
        'primary_muscles' => 'array',
        'secondary_muscles' => 'array',
        'equipment' => 'array',
    ];
}
