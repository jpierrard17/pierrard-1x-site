<?php

return [
    'api_base_url' => env('HEVY_API_BASE_URL', 'https://api.hevy.com/v1'),
    'api_key' => env('HEVY_API_KEY'), // This will be stored per user, but can be a fallback
];
