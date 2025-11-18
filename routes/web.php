<?php

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

    Route::get('/integrations/hevy', function () {
        return Inertia::render('Integrations/Hevy');
    })->name('integrations.hevy');

    Route::get('/integrations/strava', function () {
        return Inertia::render('Integrations/Strava');
    })->name('integrations.strava');
});
