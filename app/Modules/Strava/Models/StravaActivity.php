<?php

namespace App\Modules\Strava\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StravaActivity extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'distance',
        'moving_time',
        'elapsed_time',
        'total_elevation_gain',
        'type',
        'sport_type',
        'start_date_local',
        'timezone',
        'start_latlng',
        'end_latlng',
        'map_id',
        'map_summary_polyline',
        'map_polyline',
        'gear_id',
        'description',
        'calories',
        'average_speed',
        'max_speed',
        'average_cadence',
        'average_watts',
        'average_heartrate',
        'max_heartrate',
    ];

    protected $casts = [
        'start_latlng' => 'array',
        'end_latlng' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gear()
    {
        return $this->belongsTo(StravaGear::class, 'gear_id');
    }
}
