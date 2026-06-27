<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Endpoint Registrasi dan Login (Umum)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Endpoint fallback ketika user belum login
Route::get('/login', function () {
    return response()->json([
        'success' => false,
        'message' => 'Unauthenticated. Silakan kirim Bearer Token yang valid.'
    ], 401);
})->name('login');

// Route Group untuk user yang sudah login (Umum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route Group khusus Admin (dilindungi auth:sanctum dan admin middleware)
Route::middleware(['auth:sanctum', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
    });