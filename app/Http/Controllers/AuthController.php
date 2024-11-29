<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessTokenResult;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 401);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Create a Sanctum token
        $token = $user->createToken('Login Token')->plainTextToken;

        // Return the token as a cookie
        return response()->json(['message' => 'Logged in successfully', 'token' => $token], 200)
            ->cookie(
                'jwt_token',
                $token,
                60 * 24 * 30, // 30 days
                '/',
                null,
                env('APP_ENV') === 'production', // Secure flag
                true // HttpOnly
            );
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            \Log::info('Google user retrieved:', ['user' => $googleUser]);

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->id],
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]
            );

            \Log::info('User created or retrieved:', ['user' => $user]);
            // Log the user back in
            Auth::login($user);
            // Generate Sanctum token
            $token = $user->createToken('google-login')->plainTextToken;

            // Create a cookie with the token
            $cookie = cookie(
                'jwt_token',     // Cookie name
                $token,          // Token value
                60 * 24 * 30,    // Expiration (30 days)
                '/',             // Path
                null,            // Domain
                env('APP_ENV') === 'production', // Secure flag
                true             // HTTP only flag
            );




            // Redirect to the specified URL with the cookie
            return redirect()->to(env('APP_URL') . '/admin/dashboard')->withCookie($cookie);
        } catch (\Exception $e) {
            \Log::error('Google Callback Error', ['message' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to authenticate with Google.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    public function authCheck(Request $request)
    {
        // Retrieve the token from the cookie
        $token = $request->cookie('jwt_token');
        Log::info('Received JWT Token in Auth Check: ' . $token);

        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        $user = Auth::guard('api')->user();
        if ($user) {
            Log::info('User authenticated in auth check.', ['user' => $user]);
            return response()->json(['authenticated' => true, 'user' => $user], 200);
        } else {
            Log::error('User not authenticated in auth check.');
            return response()->json(['authenticated' => false], 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            // Check if the token is a TransientToken
            if ($user->currentAccessToken() && !$user->currentAccessToken() instanceof \Laravel\Sanctum\TransientToken) {
                // Revoke the current token
                $user->currentAccessToken()->delete();
            }

            // Clear the jwt_token cookie
            $cookie = cookie('jwt_token', '', -1);

            // Clear Auth session
            Auth::guard('web')->logout();

            return redirect()->to(env('APP_URL') . '/auth/login')->withCookie($cookie);
        } catch (\Exception $e) {
            \Log::error('Logout Error', ['message' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to log out',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
