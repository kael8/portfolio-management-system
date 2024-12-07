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
    public function signup(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            // Reagister the user with auto-generated guest name
            $user = User::create([
                'name' => 'Guest#' . Str::random(5),
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'isOwner' => 0,
            ]);

            // Log the user back in Passport
            Auth::login($user);

            // Generate Passport token
            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->accessToken;


            return response()->json([
                'message' => 'User registered successfully',
                'token' => $token,
                'isOwner' => $user->isOwner,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to register user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function signin(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Attempt to authenticate the user
            if (!Auth::attempt($validatedData)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            // Get the authenticated user
            $user = Auth::user();

            // Generate Passport token
            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->accessToken;

            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token,
                'isOwner' => $user->isOwner,
            ]);
        } catch (\Exception $e) {
            Log::error('Login Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to log in',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
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

            // Redirect to the Vue route with the token as a query parameter
            return redirect()->to(env('APP_URL') . '/auth/redirect?token=' . $token . '&isOwner=' . $user->isOwner);
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
            // Optionally, you can validate the token here
            try {
                $user = Auth::guard('api')->user();
                if (!$user) {
                    Log::error('Invalid token.');
                    return response()->json([
                        'authenticated' => false,
                        'message' => 'Invalid token',
                    ], 401);
                }
            } catch (\Exception $e) {
                Log::error('Token validation error: ' . $e->getMessage());
                return response()->json([
                    'authenticated' => false,
                    'message' => 'Token validation error',
                    'error' => $e->getMessage(),
                ], 401);
            }

            $isOwner = $user->isOwner;

            return response()->json([
                'authenticated' => true,
                'isOwner' => $isOwner,
                'message' => 'Token found',
            ]);
        } catch (\Exception $e) {
            Log::error('Auth Check Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'authenticated' => false,
                'message' => 'Failed to check authentication.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Attempt to authenticate the user
            $user = Auth::guard('api')->user();

            if ($user) {
                // Revoke the current token
                $token = $user->token();
                if ($token) {
                    $token->revoke();
                }

                // Clear all cookies
                $cookies = [
                    cookie()->forget('auth_token'),
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