<?php

use App\Modules\Hevy\Services\HevyService;

$user = \App\Models\User::first();
if (!$user || !$user->hevy_api_key) {
    echo "No user with Hevy API key found.\n";
    exit(1);
}

$service = new HevyService($user->hevy_api_key);

try {
    echo "Fetching frequency data...\n";
    $frequency = $service->getWorkoutFrequencyData();
    echo "Frequency Data: " . json_encode($frequency) . "\n";

    echo "Fetching volume data...\n";
    $volume = $service->getVolumeProgressData();
    echo "Volume Data: " . json_encode($volume) . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
