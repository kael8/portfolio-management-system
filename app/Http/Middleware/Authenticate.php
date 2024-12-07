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
        $authorizationHeader = $request->header('Authorization');
        if ($authorizationHeader && preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $token = $matches[1];
            Log::info('Received JWT Token in Auth Middleware: ' . $token);
            $request->headers->set('Authorization', 'Bearer ' . $token);
        } else {
            Log::warning('No JWT Token found in the Authorization header.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('api')->user();

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
