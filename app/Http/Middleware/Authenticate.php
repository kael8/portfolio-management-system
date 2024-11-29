<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate extends \Illuminate\Auth\Middleware\Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        Log::info('Entering Authenticate middleware.');

        // Attempt to authenticate the user
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            Log::warning('User not authenticated.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Log::info('User authenticated.', ['user_id' => $user->id]);

        return $next($request);
    }

    protected function redirectTo($request)
    {
        return $request->expectsJson() ? null : route('login');
    }
}
