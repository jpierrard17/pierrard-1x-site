<?php

namespace App\Modules\Hevy\Services;

use Illuminate\Support\Facades\Http;

class HevyService
{
    protected ?string $apiKey = null;
    protected string $baseUrl;

    public function __construct(?string $apiKey = null)
    {
        $this->baseUrl = config('hevy.api_base_url');
        if ($apiKey) {
            $this->apiKey = $apiKey;
        }
    }

    /**
     * Verify the Hevy API key.
     *
     * @throws \Exception
     */
    public function verifyApiKey(string $apiKey): bool
    {
        // Implement actual API call to verify key, e.g., fetching user profile
        // For now, a dummy check
        if (empty($apiKey)) {
            throw new \Exception('Hevy API key cannot be empty.');
        }
        // In a real scenario, you would make an API call here
        // For example:
        $response = Http::withHeaders([
            'api-key' => $apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/routines?page=1&pageSize=1"); // Using /routines endpoint for verification

        if ($response->successful()) {
            return true;
        }

        $response->throw(); // Throws an exception for 4xx or 5xx errors
    }

    /**
     * Fetch workouts from the Hevy API.
     */
    public function fetchWorkouts(): array
    {
        if (!$this->apiKey) {
            throw new \Exception('Hevy API key not set for service.');
        }

        // Example API call
        $response = Http::withHeaders([
            'api-key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/workouts"); // Replace with actual Hevy API endpoint

        if ($response->successful()) {
            return $response->json();
        }

        $response->throw(); // Throws an exception for 4xx or 5xx errors
    }

    /**
     * Fetch all workouts history.
     */
    public function fetchWorkoutsHistory(int $limit = 100): array
    {
        if (!$this->apiKey) {
            throw new \Exception('Hevy API key not set for service.');
        }

        $response = Http::withHeaders([
            'api-key' => $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/workouts", [
            'offset' => 0,
            'limit' => $limit,
        ]);

        if ($response->successful()) {
            return $response->json()['workouts'] ?? [];
        }

        $response->throw();
    }

    /**
     * Get workout frequency data (workouts per month).
     */
    public function getWorkoutFrequencyData(): array
    {
        $workouts = $this->fetchWorkoutsHistory();
        $frequency = [];

        foreach ($workouts as $workout) {
            $month = date('Y-m', strtotime($workout['start_time']));
            if (!isset($frequency[$month])) {
                $frequency[$month] = 0;
            }
            $frequency[$month]++;
        }

        ksort($frequency);

        return [
            'labels' => array_keys($frequency),
            'data' => array_values($frequency),
        ];
    }

    /**
     * Get volume progress data (total volume per workout).
     */
    public function getVolumeProgressData(): array
    {
        $workouts = $this->fetchWorkoutsHistory();
        $volumeData = [];

        foreach ($workouts as $workout) {
            $volume = 0;
            foreach ($workout['exercises'] as $exercise) {
                foreach ($exercise['sets'] as $set) {
                    $volume += ($set['weight_kg'] ?? 0) * ($set['reps'] ?? 0);
                }
            }
            $volumeData[] = [
                'date' => date('Y-m-d', strtotime($workout['start_time'])),
                'volume' => $volume,
            ];
        }

        // Sort by date ascending
        usort($volumeData, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return [
            'labels' => array_column($volumeData, 'date'),
            'data' => array_column($volumeData, 'volume'),
        ];
    }
}
