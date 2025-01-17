<?php
// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

use App\Http\Middleware\JwtMiddleware;

// Auth Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware(JwtMiddleware::class);

// User Routes
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{user_id}', [UserController::class, 'show']);
    Route::patch('/user/{user_id}', [UserController::class, 'update']);
    Route::delete('/user/{user_id}', [UserController::class, 'destroy']);
    Route::post('/user/{user_id}/subscription/{subscription_id}', [UserController::class, 'assignSubscription']);
    Route::patch('/user/{user_id}/subscription/{subscription_id}', [UserController::class, 'updateSubscription']);
    Route::delete('/user/{user_id}', [UserController::class, 'unassignSubscription']);
});

// Profile Routes
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::get('/profile/{profile_id}', [ProfileController::class, 'show']);
    Route::put('/profile/{profile_id}', [ProfileController::class, 'update']);
    Route::delete('/profile/{profile_id}', [ProfileController::class, 'destroy']);
    Route::get('/profile/{profile_id}/picture', [ProfileController::class, 'getProfilePicture']);
});

// Language Route
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get('/languages', [LanguageController::class, 'index']);
});

// Auth Profile Route
Route::middleware('auth:api')->get('/auth/profile', [AuthController::class, 'profile']); // Use /auth/profile

// User Profile Routes
Route::middleware(JwtMiddleware::class)->group(function () {
    // Get the profile of the currently authenticated user
    Route::get('/user/profile', [ProfileController::class, 'getCurrentUserProfile']);
    
    // Get the favorite content of the currently authenticated user
    Route::get('/user/favorite-content', [ProfileController::class, 'getFavoriteContent']);
});

// Content Routes
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get('/content', [ContentController::class, 'index']);
    Route::get('/content/{content_id}', [ContentController::class, 'show']);
    Route::get('/content/recommendations', [ContentController::class, 'recommendations']);
    Route::get('/content/search', [ContentController::class, 'search']);
});

// Subscription Routes
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index']);
    Route::post('/subscription', [SubscriptionController::class, 'store']);
    Route::put('/subscription/{subscription_id}', [SubscriptionController::class, 'update']);
    Route::delete('/subscription/{subscription_id}', [SubscriptionController::class, 'destroy']);
});