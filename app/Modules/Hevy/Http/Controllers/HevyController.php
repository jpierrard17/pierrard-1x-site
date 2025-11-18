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

    public function __construct(HevyService $hevyService)
    {
        $this->hevyService = $hevyService;
    }

    /**
     * Display the Hevy integration settings page.
     */
    public function index(): Response
    {
        return Inertia::render('Integrations/Hevy', [
            'hevyConnected' => auth()->user()->hevy_api_key !== null, // Assuming a hevy_api_key field on User model
        ]);
    }

    /**
     * Store the Hevy API key.
     */
    public function storeApiKey(HevyAuthRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->hevy_api_key = $request->input('api_key');
        $user->save();

        // Optionally, verify the API key with HevyService
        // try {
        //     $this->hevyService->verifyApiKey($request->input('api_key'));
        // } catch (\Exception $e) {
        //     // Handle invalid API key
        //     $user->hevy_api_key = null;
        //     $user->save();
        //     return back()->withErrors(['api_key' => 'Invalid Hevy API key.']);
        // }

        return back()->with('success', 'Hevy API key saved successfully.');
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
}
