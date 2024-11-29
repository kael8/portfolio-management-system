<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Http\Controllers\ClientController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/login', function (Request $request) {
    return 'Success';
});

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Authenticated routes (requires auth:api middleware)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth-check', [AuthController::class, 'authCheck']);

    // Skills routes
    Route::get('/skills', [SkillController::class, 'getSkills']);
    Route::post('/skills', [SkillController::class, 'skills']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile', [ProfileController::class, 'profile']);
    Route::get('/profile-image', [ProfileController::class, 'getProfileImage']);
    Route::post('/profile-image', [ProfileController::class, 'profileImage']);

    // Projects routes
    Route::get('/projects', [ProjectController::class, 'getProjects']);
    Route::post('/projects', [ProjectController::class, 'create']);
    Route::post('/projects/{project_id}/update', [ProjectController::class, 'update']);
    Route::delete('/projects/{project_id}', [ProjectController::class, 'delete']);
});

// Passport routes with API prefix and appropriate middleware
Route::prefix('oauth')->middleware('api')->group(function () {
    Route::post('/token', [AccessTokenController::class, 'issueToken'])->middleware('throttle');

    Route::middleware('auth:api')->group(function () {
        Route::get('/tokens', [AuthorizedAccessTokenController::class, 'forUser']);
        Route::delete('/tokens/{token_id}', [AuthorizedAccessTokenController::class, 'destroy']);
        Route::post('/clients', [ClientController::class, 'store']);
        Route::put('/clients/{client_id}', [ClientController::class, 'update']);
        Route::delete('/clients/{client_id}', [ClientController::class, 'destroy']);
        Route::get('/scopes', [ClientController::class, 'all']);
        Route::get('/personal-access-tokens', [PersonalAccessTokenController::class, 'forUser']);
        Route::post('/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
        Route::delete('/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);
    });
});
