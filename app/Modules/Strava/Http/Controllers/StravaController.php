<?php

namespace App\Modules\Strava\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Strava\Services\StravaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StravaController extends Controller
{
    protected StravaService $stravaService;

    public function __construct(StravaService $stravaService)
    {
        $this->stravaService = $stravaService;
    }

    public function index()
    {
        $user = Auth::user();
        return Inertia::render('Integrations/Strava', [
            'stravaConnected' => !empty($user->strava_access_token),
        ]);
    }

    public function connect()
    {
        return Inertia::location($this->stravaService->authorizationUrl());
    }

    public function callback(Request $request)
    {
        $code = $request->query('code');
        $error = $request->query('error');

        if ($error) {
            return redirect()->route('integrations.strava')->with('flash', [
                'error' => 'Strava authorization failed: ' . $error,
            ]);
        }

        if (!$code) {
             return redirect()->route('integrations.strava')->with('flash', [
                'error' => 'No authorization code returned from Strava.',
            ]);
        }

        try {
            $tokenData = $this->stravaService->exchangeToken($code);
            
            $user = Auth::user();
            $user->strava_access_token = $tokenData['access_token'];
            $user->strava_refresh_token = $tokenData['refresh_token'];
            // We could also store expires_at if we added that column
            
            return redirect()->route('integrations.strava')->with('flash', [
                'success' => 'Successfully connected to Strava!',
            ]);

        } catch (\Exception $e) {
            return redirect()->route('integrations.strava')->with('flash', [
                'error' => 'Failed to exchange token: ' . $e->getMessage(),
            ]);
        }
    }

    public function disconnect()
    {
        $user = Auth::user();
        $user->strava_access_token = null;
        $user->strava_refresh_token = null;
        
        return redirect()->back()->with('flash', [
            'success' => 'Disconnected from Strava.',
        ]);
    }

    public function sync()
    {
        try {
            $user = Auth::user();
            $stats = $this->stravaService->syncActivities($user);
            
            return response()->json([
                'message' => 'Sync complete',
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fetch chart data for visualizations.
     */
    public function fetchChartData()
    {
        try {
            $frequency = $this->stravaService->getActivityFrequencyData();
            $distance = $this->stravaService->getDistanceProgressData();
            $elevation = $this->stravaService->getElevationProgressData();
            $breakdown = $this->stravaService->getActivityTypeBreakdown();
            $pace = $this->stravaService->getPaceAnalysis('Run');

            return response()->json([
                'frequency' => $frequency,
                'distance' => $distance,
                'elevation' => $elevation,
                'breakdown' => $breakdown,
                'pace' => $pace,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch chart data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fetch activities with routes for mapping.
     */
    public function fetchActivitiesWithRoutes(Request $request)
    {
        try {
            $type = $request->query('type');
            $limit = $request->query('limit', 50);
            
            $activities = $this->stravaService->getActivitiesWithRoutes($type, $limit);
            
            return response()->json($activities);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch activities: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fetch heatmap data.
     */
    public function fetchHeatmapData(Request $request)
    {
        try {
            $minOccurrences = $request->query('min_occurrences', 5);
            
            $data = $this->stravaService->getHeatmapData($minOccurrences);
            
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch heatmap data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
