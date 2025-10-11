<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

Route::post('/login', [LoginController::class, 'store'])
                ->middleware('guest');

// Email Verification Routes
Route::get('/email/verify/{id}/{hash}', [RegisteredUserController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/resend', [RegisteredUserController::class, 'resend'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
