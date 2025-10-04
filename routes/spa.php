<?php

use App\Http\Controllers\SpaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SPA Routes
|--------------------------------------------------------------------------
|
| Here is where you can register SPA routes for your frontend. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "spa" middleware group.
|
*/

// Admin area: notify access attempts then apply IP allowlist. Must be defined before the catch-all route.
Route::middleware(['admin.access.notify', 'ip.allowlist'])->get('admin/{path?}', SpaController::class)->where('path', '(.*)');

// Catch-all: all other SPA routes
Route::get('{path}', SpaController::class)->where('path', '(.*)');
