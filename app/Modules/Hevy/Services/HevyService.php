<?php

namespace App\Modules\Hevy\Services;

use Illuminate\Support\Facades\Http;

class HevyService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('hevy.api_base_url');
    }

    /**
     * Set the API key for the service.
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Verify the Hevy API key.
     *
     * @throws \Exception
     */
    public function verifyApiKey(string $apiKey): bool
    {
        $this->setApiKey($apiKey);
        // Implement actual API call to verify key, e.g., fetching user profile
        // For now, a dummy check
        if (empty($apiKey)) {
            throw new \Exception('Hevy API key cannot be empty.');
        }
        return true;
    }

    /**
     * Fetch workouts from the Hevy API.
     */
    public function fetchWorkouts(): array
    {
        // Example API call
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/workouts"); // Replace with actual Hevy API endpoint

        if ($response->successful()) {
            return $response->json();
        }

        $response->throw(); // Throws an exception for 4xx or 5xx errors
    }

    // Add other methods for interacting with the Hevy API as needed
}
