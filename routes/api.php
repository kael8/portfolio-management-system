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
use App\Http\Controllers\AboutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\VisitorController;

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

Route::post('/log-visitor', [VisitorController::class, 'logVisitor']);
Route::get('/visitors', [VisitorController::class, 'getVisitors']);

Route::get('/profile', [ProfileController::class, 'getProfile']);
Route::get('/profile-image', [ProfileController::class, 'getProfileImage']);
Route::get('/skills', [SkillController::class, 'getSkills']);
Route::get('/projects', [ProjectController::class, 'getProjects']);
Route::get('/about', [AboutController::class, 'getAbout']);
Route::get('/get-posts', [PostController::class, 'getPosts']);

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/signin', [AuthController::class, 'signin']);


// Authenticated routes (requires auth:api middleware)
Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/auth-check', [AuthController::class, 'authCheck']);

    Route::post('/update-account', [AccountController::class, 'updateAccount']);

    Route::get('/get-account', [AccountController::class, 'getAccount']);

    // Reaction routes
    Route::post('/reaction', [ReactionController::class, 'store']);

    // Comment routes
    Route::post('/create-comment', [CommentController::class, 'create']);
});

Route::middleware(['auth:api', 'check.user.type'])->group(function () {
    // Skills routes
    Route::post('/skills', [SkillController::class, 'skills']);

    // Profile routes
    Route::post('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile-image', [ProfileController::class, 'profileImage']);

    // Projects routes
    Route::post('/projects', [ProjectController::class, 'create']);
    Route::post('/projects/{project_id}/update', [ProjectController::class, 'update']);
    Route::delete('/projects/{project_id}', [ProjectController::class, 'delete']);

    // About routes
    Route::post('/about', [AboutController::class, 'about']);

    // Post routes
    Route::post('/create-post', [PostController::class, 'create']);
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