<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        // If the request is authenticated via a personal access token (Sanctum),
        // delete the current access token so it cannot be used again.
        if ($request->user() && method_exists($request->user(), 'currentAccessToken')) {
            $token = $request->user()->currentAccessToken();
            if ($token) {
                $token->delete();
            }
        }

        // Also perform the normal session logout to cover cookie-based (web/Sanctum SPA) auth.
        // Some API requests (routes/api.php) are stateless and won't have a session store
        // bound to the request. Guard session operations with hasSession() to avoid
        // the "Session store not set on request." exception.
        if ($request->hasSession()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Return JSON for API requests, otherwise redirect to home for web requests.
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['message' => 'Logged out'], 200);
        }

        return redirect('/');
    }
}
