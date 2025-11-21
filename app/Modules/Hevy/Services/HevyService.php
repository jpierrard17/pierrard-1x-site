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
     * Sync workouts from Hevy API to local database.
     */
    public function syncWorkouts(int $userId): array
    {
        if (!$this->apiKey) {
            throw new \Exception('Hevy API key not set for service.');
        }

        $added = 0;
        $skipped = 0;
        $page = 0;
        $pageSize = 10; // API maximum
        $keepFetching = true;

        while ($keepFetching) {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ])->get("{$this->baseUrl}/workouts", [
                'page' => $page,
                'pageSize' => $pageSize,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch workouts from Hevy: ' . $response->body());
            }

            $data = $response->json();
            $workouts = $data['workouts'] ?? [];

            if (empty($workouts)) {
                $keepFetching = false;
                break;
            }

            $pageSkipped = 0;

            foreach ($workouts as $workoutData) {
                $exists = \App\Modules\Hevy\Models\HevyWorkout::where('id', $workoutData['id'])->exists();

                if ($exists) {
                    $skipped++;
                    $pageSkipped++;
                    continue;
                }

                // Create Workout
                $workout = \App\Modules\Hevy\Models\HevyWorkout::create([
                    'id' => $workoutData['id'],
                    'user_id' => $userId,
                    'title' => $workoutData['title'],
                    'description' => $workoutData['description'],
                    'start_time' => \Carbon\Carbon::parse($workoutData['start_time']),
                    'end_time' => \Carbon\Carbon::parse($workoutData['end_time']),
                    'hevy_created_at' => \Carbon\Carbon::parse($workoutData['created_at']),
                    'hevy_updated_at' => \Carbon\Carbon::parse($workoutData['updated_at']),
                ]);

                // Create Exercises and Sets
                foreach ($workoutData['exercises'] as $exerciseData) {
                    // Ensure Exercise Template exists
                    $templateExists = \App\Modules\Hevy\Models\HevyExerciseTemplate::where('id', $exerciseData['exercise_template_id'])->exists();
                    if (!$templateExists) {
                        \App\Modules\Hevy\Models\HevyExerciseTemplate::create([
                            'id' => $exerciseData['exercise_template_id'],
                            'name' => $exerciseData['title'], // Use title as fallback name
                            'type' => $exerciseData['exercise_type'] ?? 'weight',
                            'primary_muscles' => [], // Default empty
                            'secondary_muscles' => [], // Default empty
                            'equipment' => [], // Default empty
                        ]);
                    }

                    $workoutExercise = \App\Modules\Hevy\Models\HevyWorkoutExercise::create([
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'hevy_workout_id' => $workout->id,
                        'exercise_template_id' => $exerciseData['exercise_template_id'],
                        'title' => $exerciseData['title'],
                        'notes' => $exerciseData['notes'],
                        'superset_id' => $exerciseData['superset_id'] ?? null,
                        'exercise_type' => $exerciseData['exercise_type'] ?? 'weight', // Default or map correctly
                    ]);

                    foreach ($exerciseData['sets'] as $setData) {
                        \App\Modules\Hevy\Models\HevyWorkoutSet::create([
                            'id' => (string) \Illuminate\Support\Str::uuid(),
                            'hevy_workout_exercise_id' => $workoutExercise->id,
                            'index' => $setData['index'] ?? 0,
                            'set_type' => $setData['set_type'] ?? 'normal',
                            'weight_kg' => $setData['weight_kg'],
                            'reps' => $setData['reps'],
                            'distance_meters' => $setData['distance_meters'],
                            'duration_seconds' => $setData['duration_seconds'],
                            'rpe' => $setData['rpe'],
                        ]);
                    }
                }

                $added++;
            }

            if ($pageSkipped === count($workouts)) {
                $keepFetching = false;
            }

            $page++;
        }

        return [
            'added' => $added,
            'skipped' => $skipped,
        ];
    }

    /**
     * Get workout frequency data (workouts per month) from local DB.
     */
    public function getWorkoutFrequencyData(): array
    {
        $workouts = \App\Modules\Hevy\Models\HevyWorkout::orderBy('start_time')->get();
        $frequency = [];

        foreach ($workouts as $workout) {
            $month = $workout->start_time->format('Y-m');
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
     * Get volume progress data (total volume per workout) from local DB.
     */
    public function getVolumeProgressData(): array
    {
        $workouts = \App\Modules\Hevy\Models\HevyWorkout::with('exercises.sets')->orderBy('start_time')->get();
        $volumeData = [];

        foreach ($workouts as $workout) {
            $volume = 0;
            foreach ($workout->exercises as $exercise) {
                foreach ($exercise->sets as $set) {
                    $volume += ($set->weight_kg ?? 0) * ($set->reps ?? 0);
                }
            }
            $volumeData[] = [
                'date' => $workout->start_time->format('Y-m-d'),
                'volume' => $volume,
            ];
        }

        return [
            'labels' => array_column($volumeData, 'date'),
            'data' => array_column($volumeData, 'volume'),
        ];
    }

    /**
     * Get list of all exercises the user has performed.
     */
    public function getAvailableExercises(): array
    {
        $exercises = \App\Modules\Hevy\Models\HevyWorkoutExercise::query()
            ->join('hevy_exercise_templates', 'hevy_workout_exercises.exercise_template_id', '=', 'hevy_exercise_templates.id')
            ->select('hevy_exercise_templates.id', 'hevy_exercise_templates.name', \DB::raw('COUNT(*) as count'))
            ->groupBy('hevy_exercise_templates.id', 'hevy_exercise_templates.name')
            ->orderByDesc('count')
            ->get();

        return $exercises->map(function ($exercise) {
            return [
                'id' => $exercise->id,
                'name' => $exercise->name,
                'count' => $exercise->count,
            ];
        })->toArray();
    }

    /**
     * Get progress data for a specific exercise.
     */
    public function getExerciseProgressData(string $exerciseTemplateId): array
    {
        $workouts = \App\Modules\Hevy\Models\HevyWorkout::query()
            ->whereHas('exercises', function ($query) use ($exerciseTemplateId) {
                $query->where('exercise_template_id', $exerciseTemplateId);
            })
            ->with(['exercises' => function ($query) use ($exerciseTemplateId) {
                $query->where('exercise_template_id', $exerciseTemplateId)
                    ->with('sets');
            }])
            ->orderBy('start_time')
            ->get();

        $progressData = [];

        foreach ($workouts as $workout) {
            $maxWeight = 0;
            $totalVolume = 0;
            $maxEstimated1RM = 0;

            foreach ($workout->exercises as $exercise) {
                foreach ($exercise->sets as $set) {
                    $weight = $set->weight_kg ?? 0;
                    $reps = $set->reps ?? 0;

                    // Track max weight
                    if ($weight > $maxWeight) {
                        $maxWeight = $weight;
                    }

                    // Calculate volume
                    $totalVolume += $weight * $reps;

                    // Calculate estimated 1RM (Epley formula)
                    if ($weight > 0 && $reps > 0) {
                        $estimated1RM = $this->calculateEstimated1RM($weight, $reps);
                        if ($estimated1RM > $maxEstimated1RM) {
                            $maxEstimated1RM = $estimated1RM;
                        }
                    }
                }
            }

            // Convert kg to lbs for display (1 kg = 2.20462 lbs)
            $progressData[] = [
                'date' => $workout->start_time->format('Y-m-d'),
                'maxWeight' => round($maxWeight * 2.20462, 2),
                'volume' => round($totalVolume * 2.20462, 2),
                'estimated1RM' => round($maxEstimated1RM * 2.20462, 2),
            ];
        }

        return [
            'labels' => array_column($progressData, 'date'),
            'maxWeight' => array_column($progressData, 'maxWeight'),
            'volume' => array_column($progressData, 'volume'),
            'estimated1RM' => array_column($progressData, 'estimated1RM'),
        ];
    }

    /**
     * Calculate estimated 1RM using Epley formula.
     */
    private function calculateEstimated1RM(float $weight, int $reps): float
    {
        // Epley formula: weight × (1 + reps/30)
        return $weight * (1 + ($reps / 30));
    }
}

