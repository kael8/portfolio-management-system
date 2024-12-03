<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            Log::info('Starting Google Callback', [
                'client_id' => config('services.google.client_id'),
                'redirect_uri' => config('services.google.redirect')
            ]);

            $googleUser = Socialite::driver('google')->stateless()->user();
            Log::info('Google User Retrieved', [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'id' => $googleUser->id
            ]);

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => bcrypt(Str::random(40)),
                    'google_id' => $googleUser->id,
                ]
            );

            Log::info('User created or retrieved:', ['user' => $user]);

            // Log the user back in Passport
            Auth::login($user);

            // Generate Passport token
            $tokenResult = $user->createToken('google-login');
            $token = $tokenResult->accessToken;
            Log::info('Generated Passport Token: ' . $token);

            // Create a cookie with the token
            $cookie = cookie(
                'jwt_token',     // Cookie name
                $token,          // Token value
                60 * 24 * 30,    // Expiration (30 days)
                '/',             // Path
                null,            // Domain
                false,           // Secure flag (set to false for local testing)
                true,             // HTTP only flag
            );

            Log::info('Setting JWT Token in Cookie: ' . $cookie->getValue());

            // Redirect to the specified URL with the cookie
            return redirect()->to(env('APP_URL') . '/auth/redirect')->withCookie($cookie);
        } catch (\Exception $e) {
            Log::error('Google Callback Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to authenticate with Google.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function authCheck(Request $request)
    {
        try {
            // Retrieve the token from the cookie
            $token = $request->cookie('jwt_token');
            Log::info('Received JWT Token in Auth Check: ' . $token);

            if (!$token) {
                Log::error('No JWT Token found in the cookie.');
                return response()->json([
                    'authenticated' => false,
                    'message' => 'No token found'
                ], 401);
            }

            $request->headers->set('Authorization', 'Bearer ' . $token);

            // Attempt to authenticate the user
            $user = Auth::guard('api')->user();
            Log::info('Authenticated User:', ['user' => $user]);

            if (!$user) {
                Log::error('User not authenticated in auth check.');
                return response()->json([
                    'authenticated' => false,
                    'message' => 'Invalid token'
                ], 401);
            }

            return response()->json([
                'authenticated' => true,
                'user' => $user->only(['id', 'name', 'email']),
                'scopes' => $user->token()->scopes ?? []
            ], 200);
        } catch (\Exception $e) {
            Log::error('Auth Check Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'authenticated' => false,
                'message' => 'Authentication error'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Retrieve the token from the cookie
            $token = $request->cookie('jwt_token');

            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
            }

            // Attempt to authenticate the user
            $user = Auth::guard('api')->user();

            if ($user) {
                // Revoke the current token
                $user->token()->revoke();

                // Clear all cookies
                $cookies = [
                    cookie()->forget('jwt_token'),
                    cookie()->forget('XSRF-TOKEN'),
                    cookie()->forget(env('APP_NAME') . '_session')
                ];

                // Clear Auth session
                Auth::guard('web')->logout();

                return redirect()->to(env('APP_URL') . '/auth/login')->withCookies($cookies);
            } else {
                Log::warning('User not authenticated during logout.');
                return response()->json(['message' => 'User not authenticated'], 401);
            }
        } catch (\Exception $e) {
            Log::error('Logout Error', ['message' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to log out',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}