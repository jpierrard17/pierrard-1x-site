<?php

namespace App\Modules\Strava\Services;

use App\Models\User;
use App\Modules\Strava\Models\StravaActivity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StravaService
{
    protected string $baseUrl = 'https://www.strava.com/api/v3';
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.strava.client_id');
        $this->clientSecret = config('services.strava.client_secret');
        $this->redirectUri = route('integrations.strava.callback');
    }

    public function authorizationUrl(): string
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'read,activity:read_all',
            'approval_prompt' => 'auto',
        ]);

        return "https://www.strava.com/oauth/authorize?{$query}";
    }

    public function exchangeToken(string $code): array
    {
        $response = Http::post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);

        return $response->json();
    }

    public function refreshToken(User $user): ?string
    {
        $refreshToken = $user->strava_refresh_token;

        if (!$refreshToken) {
            return null;
        }

        $response = Http::post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $user->strava_access_token = $data['access_token'];
            $user->strava_refresh_token = $data['refresh_token'];
            return $data['access_token'];
        }

        return null;
    }

    public function syncActivities(User $user): array
    {
        $accessToken = $user->strava_access_token;
        
        if (!$accessToken) {
             // Try to refresh
             $accessToken = $this->refreshToken($user);
             if (!$accessToken) {
                 throw new \Exception('Strava not connected or token expired.');
             }
        }

        $added = 0;
        $skipped = 0;
        $page = 1;
        $perPage = 30;
        $keepFetching = true;

        while ($keepFetching) {
            $response = Http::withToken($accessToken)->get("{$this->baseUrl}/athlete/activities", [
                'page' => $page,
                'per_page' => $perPage,
            ]);

            if ($response->status() === 401) {
                // Token might be expired, try refreshing once
                $accessToken = $this->refreshToken($user);
                if ($accessToken) {
                    $response = Http::withToken($accessToken)->get("{$this->baseUrl}/athlete/activities", [
                        'page' => $page,
                        'per_page' => $perPage,
                    ]);
                } else {
                    throw new \Exception('Failed to refresh Strava token.');
                }
            }

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch activities from Strava: ' . $response->body());
            }

            $activities = $response->json();

            if (empty($activities)) {
                $keepFetching = false;
                break;
            }

            foreach ($activities as $activityData) {
                $exists = StravaActivity::where('id', $activityData['id'])->exists();

                if ($exists) {
                    $skipped++;
                    $keepFetching = false; // Delta sync: stop when we find existing
                    continue;
                }

                StravaActivity::create([
                    'id' => $activityData['id'],
                    'user_id' => $user->id,
                    'name' => $activityData['name'],
                    'distance' => $activityData['distance'],
                    'moving_time' => $activityData['moving_time'],
                    'elapsed_time' => $activityData['elapsed_time'],
                    'total_elevation_gain' => $activityData['total_elevation_gain'],
                    'type' => $activityData['type'],
                    'sport_type' => $activityData['sport_type'],
                    'start_date' => Carbon::parse($activityData['start_date']),
                    'start_date_local' => Carbon::parse($activityData['start_date_local']),
                    'timezone' => $activityData['timezone'],
                    'utc_offset' => $activityData['utc_offset'],
                    'map_id' => $activityData['map']['id'] ?? null,
                    'map_summary_polyline' => $activityData['map']['summary_polyline'] ?? null,
                    'average_speed' => $activityData['average_speed'],
                    'max_speed' => $activityData['max_speed'],
                    'average_cadence' => $activityData['average_cadence'] ?? null,
                    'average_temp' => $activityData['average_temp'] ?? null,
                    'average_heartrate' => $activityData['average_heartrate'] ?? null,
                    'max_heartrate' => $activityData['max_heartrate'] ?? null,
                    'elev_high' => $activityData['elev_high'] ?? null,
                    'elev_low' => $activityData['elev_low'] ?? null,
                    'pr_count' => $activityData['pr_count'] ?? 0,
                    'achievement_count' => $activityData['achievement_count'] ?? 0,
                    'kudos_count' => $activityData['kudos_count'] ?? 0,
                    'comment_count' => $activityData['comment_count'] ?? 0,
                    'athlete_count' => $activityData['athlete_count'] ?? 1,
                    'photo_count' => $activityData['photo_count'] ?? 0,
                    'trainer' => $activityData['trainer'] ?? false,
                    'commute' => $activityData['commute'] ?? false,
                    'manual' => $activityData['manual'] ?? false,
                    'private' => $activityData['private'] ?? false,
                    'flagged' => $activityData['flagged'] ?? false,
                    'gear_id' => $activityData['gear_id'] ?? null,
                    'external_id' => $activityData['external_id'] ?? null,
                    'upload_id' => $activityData['upload_id'] ?? null,
                ]);

                $added++;
            }
            $page++;
        }

        return ['added' => $added, 'skipped' => $skipped];
    }

    /**
     * Get activity frequency data (activities per month).
     */
    public function getActivityFrequencyData(): array
    {
        $activities = StravaActivity::orderBy('start_date_local')->get();
        $frequency = [];

        foreach ($activities as $activity) {
            $month = Carbon::parse($activity->start_date_local)->format('Y-m');
            if (!isset($frequency[$month])) {
                $frequency[$month] = 0;
            }
            $frequency[$month]++;
        }

        return [
            'labels' => array_keys($frequency),
            'data' => array_values($frequency),
        ];
    }

    /**
     * Get distance progress data (total km per month).
     */
    public function getDistanceProgressData(): array
    {
        $activities = StravaActivity::orderBy('start_date_local')->get();
        $distanceData = [];

        foreach ($activities as $activity) {
            $month = Carbon::parse($activity->start_date_local)->format('Y-m');
            if (!isset($distanceData[$month])) {
                $distanceData[$month] = 0;
            }
            // Convert meters to km
            $distanceData[$month] += $activity->distance / 1000;
        }

        return [
            'labels' => array_keys($distanceData),
            'data' => array_map(fn($d) => round($d, 2), array_values($distanceData)),
        ];
    }

    /**
     * Get elevation progress data (total feet per month).
     */
    public function getElevationProgressData(): array
    {
        $activities = StravaActivity::orderBy('start_date_local')->get();
        $elevationData = [];

        foreach ($activities as $activity) {
            $month = Carbon::parse($activity->start_date_local)->format('Y-m');
            if (!isset($elevationData[$month])) {
                $elevationData[$month] = 0;
            }
            // Convert meters to feet
            $elevationData[$month] += $activity->total_elevation_gain * 3.28084;
        }

        return [
            'labels' => array_keys($elevationData),
            'data' => array_map(fn($e) => round($e, 2), array_values($elevationData)),
        ];
    }

    /**
     * Get activity type breakdown.
     */
    public function getActivityTypeBreakdown(): array
    {
        $breakdown = StravaActivity::select('type', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(distance) as total_distance'))
            ->groupBy('type')
            ->orderByDesc('count')
            ->get();

        return [
            'labels' => $breakdown->pluck('type')->toArray(),
            'counts' => $breakdown->pluck('count')->toArray(),
            'distances' => $breakdown->pluck('total_distance')->map(fn($d) => round($d / 1000, 2))->toArray(),
        ];
    }

    /**
     * Get pace analysis for a specific activity type.
     */
    public function getPaceAnalysis(string $activityType = 'Run'): array
    {
        $activities = StravaActivity::where('type', $activityType)
            ->where('distance', '>', 0)
            ->orderBy('start_date_local')
            ->get();

        $paceData = [];

        foreach ($activities as $activity) {
            $date = Carbon::parse($activity->start_date_local)->format('Y-m-d');
            $distanceKm = $activity->distance / 1000;
            $timeMinutes = $activity->moving_time / 60;
            
            if ($distanceKm > 0) {
                $paceMinPerKm = $timeMinutes / $distanceKm;
                $paceData[] = [
                    'date' => $date,
                    'pace' => round($paceMinPerKm, 2),
                ];
            }
        }

        return [
            'labels' => array_column($paceData, 'date'),
            'data' => array_column($paceData, 'pace'),
        ];
    }

    /**
     * Get activities with routes for mapping.
     */
    public function getActivitiesWithRoutes(?string $type = null, int $limit = 50): array
    {
        $query = StravaActivity::whereNotNull('map_summary_polyline')
            ->orderBy('start_date_local', 'desc')
            ->limit($limit);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->get()->map(function ($activity) {
            return [
                'id' => $activity->id,
                'name' => $activity->name,
                'type' => $activity->type,
                'date' => Carbon::parse($activity->start_date_local)->format('Y-m-d H:i'),
                'distance' => round($activity->distance / 1000, 2), // km
                'polyline' => $activity->map_summary_polyline,
            ];
        })->toArray();
    }

    /**
     * Get heatmap data (routes with minimum occurrences).
     */
    public function getHeatmapData(int $minOccurrences = 5): array
    {
        // Get all runs and walks with polylines
        $activities = StravaActivity::whereIn('type', ['Run', 'Walk'])
            ->whereNotNull('map_summary_polyline')
            ->select('map_summary_polyline')
            ->get();

        // Collect all polylines for heatmap - use values() to ensure it's a proper array
        $polylines = $activities->pluck('map_summary_polyline')->filter()->values()->toArray();

        return [
            'polylines' => $polylines,
            'minOccurrences' => $minOccurrences,
        ];
    }
}
