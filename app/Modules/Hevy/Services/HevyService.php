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
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/user/profile"); // Or some other lightweight endpoint

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
