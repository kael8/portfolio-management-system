<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('Entering CheckUserType middleware.');

        // Attempt to authenticate the user
        $token = $request->cookie('jwt_token');
        Log::info('Received JWT Token in CheckUserType Middleware: ' . $token);

        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        $user = Auth::guard('api')->user();

        if (!$user) {
            Log::warning('User not authenticated.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$user->isOwner) {
            Log::warning('User is not an owner.', ['user_id' => $user->id]);
            return response()->json(['message' => 'Forbidden: Admin use only. Guests are not allowed to use this function.'], 403);
        }

        Log::info('User is an owner.', ['user_id' => $user->id]);

        return $next($request);
    }
}