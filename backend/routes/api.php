<?php
// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Auth Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth.ensure');
Route::post('/auth/password-reset', [AuthController::class, 'resetPassword']);

// Password Reset Routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// User Routes
Route::middleware('auth.ensure')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/{user_id}', [UserController::class, 'show']);
    Route::put('/user/{user_id}', [UserController::class, 'update']);
    Route::delete('/user/{user_id}', [UserController::class, 'destroy']);
    Route::post('/user/{user_id}/subscription/{subscription_id}', [UserController::class, 'assignSubscription']);
    Route::patch('/user/{user_id}/subscription/{subscription_id}', [UserController::class, 'updateSubscription']);
    Route::delete('/user/{user_id}', [UserController::class, 'unassignSubscription']);
});

// Profile Routes
Route::middleware('auth.ensure')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile', [ProfileController::class, 'store']);
    Route::get('/profile/{profile_id}', [ProfileController::class, 'show']);
    Route::put('/profile/{profile_id}', [ProfileController::class, 'update']);
    Route::delete('/profile/{profile_id}', [ProfileController::class, 'destroy']);
});

// Content Routes
Route::middleware('auth.ensure')->group(function () {
    Route::get('/content', [ContentController::class, 'index']);
    Route::get('/content/{content_id}', [ContentController::class, 'show']);
    Route::get('/content/recommendations', [ContentController::class, 'recommendations']);
    Route::get('/content/search', [ContentController::class, 'search']);
});

// Subscription Routes
Route::middleware('auth.ensure')->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index']);
    Route::post('/subscription', [SubscriptionController::class, 'store']);
    Route::put('/subscription/{subscription_id}', [SubscriptionController::class, 'update']);
    Route::delete('/subscription/{subscription_id}', [SubscriptionController::class, 'destroy']);
});
