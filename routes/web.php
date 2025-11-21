<?php

use App\Modules\Hevy\Http\Controllers\HevyController;
use App\Modules\Strava\Http\Controllers\StravaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [ // Changed from 'Welcome' to 'Home'
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/professional', function () { // New route for Professional page
    return Inertia::render('Professional');
})->name('professional');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Integrations Routes
    Route::get('/integrations', function () {
        return Inertia::render('Integrations');
    })->name('integrations');

    // Hevy Integration Routes
    Route::get('/integrations/hevy', [HevyController::class, 'index'])->name('integrations.hevy');
    Route::post('/integrations/hevy/api-key', [HevyController::class, 'storeApiKey'])->name('integrations.hevy.store-api-key');
    Route::post('/integrations/hevy/disconnect', [HevyController::class, 'disconnect'])->name('integrations.hevy.disconnect');
    Route::get('/integrations/hevy/data', [HevyController::class, 'fetchData'])->name('integrations.hevy.fetch-data');
    Route::get('/integrations/hevy/charts', [HevyController::class, 'fetchChartData'])->name('integrations.hevy.fetch-chart-data');
    Route::get('/integrations/hevy/exercises', [HevyController::class, 'fetchExercises'])->name('integrations.hevy.fetch-exercises');
    Route::get('/integrations/hevy/exercise-progress', [HevyController::class, 'fetchExerciseProgress'])->name('integrations.hevy.fetch-exercise-progress');

    // Strava Integration Routes
    Route::get('/integrations/strava', [StravaController::class, 'index'])->name('integrations.strava');
    Route::get('/integrations/strava/connect', [StravaController::class, 'connect'])->name('integrations.strava.connect');
    Route::get('/integrations/strava/callback', [StravaController::class, 'callback'])->name('integrations.strava.callback');
    Route::post('/integrations/strava/disconnect', [StravaController::class, 'disconnect'])->name('integrations.strava.disconnect');
    Route::post('/integrations/strava/sync', [StravaController::class, 'sync'])->name('integrations.strava.sync');
    Route::get('/integrations/strava/charts', [StravaController::class, 'fetchChartData'])->name('integrations.strava.fetch-chart-data');
    Route::get('/integrations/strava/activities-with-routes', [StravaController::class, 'fetchActivitiesWithRoutes'])->name('integrations.strava.fetch-activities-with-routes');
    Route::get('/integrations/strava/heatmap', [StravaController::class, 'fetchHeatmapData'])->name('integrations.strava.fetch-heatmap-data');
});
