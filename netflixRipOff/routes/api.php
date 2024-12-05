// routes/api.php

// Auth Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout']);
Route::post('/auth/password-reset', [AuthController::class, 'resetPassword']);

// User Routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user_id}', [UserController::class, 'show']);
Route::put('/users/{user_id}', [UserController::class, 'update']);
Route::delete('/users/{user_id}', [UserController::class, 'destroy']);

// Profile Routes
Route::get('/users/{user_id}/profiles', [ProfileController::class, 'index']);
Route::post('/users/{user_id}/profiles', [ProfileController::class, 'store']);
Route::get('/users/{user_id}/profiles/{profile_id}', [ProfileController::class, 'show']);
Route::put('/users/{user_id}/profiles/{profile_id}', [ProfileController::class, 'update']);
Route::delete('/users/{user_id}/profiles/{profile_id}', [ProfileController::class, 'destroy']);

// Content Routes
Route::get('/content', [ContentController::class, 'index']);
Route::get('/content/{content_id}', [ContentController::class, 'show']);
Route::get('/content/recommendations', [ContentController::class, 'recommendations']);
Route::get('/content/search', [ContentController::class, 'search']);

// Subscription Routes
Route::get('/subscriptions', [SubscriptionController::class, 'index']);
Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::put('/subscriptions/{subscription_id}', [SubscriptionController::class, 'update']);
Route::delete('/subscriptions/{subscription_id}', [SubscriptionController::class, 'destroy']);

