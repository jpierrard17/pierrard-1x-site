<?php

namespace App\Modules\Hevy\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Hevy\Http\Requests\HevyAuthRequest;
use App\Modules\Hevy\Services\HevyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HevyController extends Controller
{
    protected HevyService $hevyService;

    public function __construct()
    {
        $user = auth()->user();
        $this->hevyService = new HevyService($user->hevy_api_key);
    }

    /**
     * Display the Hevy integration settings page.
     */
    public function index(): Response
    {
        return Inertia::render('Integrations/Hevy', [
            'hevyConnected' => auth()->user()->hevy_api_key !== null,
        ]);
    }

    /**
     * Store the Hevy API key.
     */
    public function storeApiKey(HevyAuthRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            $this->hevyService->verifyApiKey($request->input('api_key'));
            $user->hevy_api_key = $request->input('api_key');
            $user->save();
        } catch (\Exception $e) {
            // Handle invalid API key
            $user->hevy_api_key = null; // Clear potentially invalid key
            $user->save();
            return back()->withErrors(['api_key' => 'Invalid Hevy API key: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Hevy API key saved and verified successfully.');
    }

    /**
     * Disconnect Hevy integration.
     */
    public function disconnect(): RedirectResponse
    {
        $user = auth()->user();
        $user->hevy_api_key = null;
        $user->save();

        return back()->with('success', 'Hevy integration disconnected.');
    }

    /**
     * Fetch data from Hevy API.
     */
    public function fetchData(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->hevyService->fetchWorkouts(); // Example: fetching workouts
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch Hevy data: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Fetch chart data from Hevy API.
     */
    public function fetchChartData(): \Illuminate\Http\JsonResponse
    {
        try {
            $frequency = $this->hevyService->getWorkoutFrequencyData();
            $volume = $this->hevyService->getVolumeProgressData();

            return response()->json([
                'frequency' => $frequency,
                'volume' => $volume,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch Hevy chart data: ' . $e->getMessage()], 500);
        }
    }
}
