<?php

namespace App\Modules\Strava\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StravaGear extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'nickname',
        'distance',
        'brand_name',
        'model_name',
        'description',
    ];

    public function activities()
    {
        return $this->hasMany(StravaActivity::class, 'gear_id');
    }
}
