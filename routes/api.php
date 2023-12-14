<?php

use App\Http\Controllers\SystemEventController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelativeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create-token', [TokenController::class, 'createToken']);

Route::middleware(['auth:sanctum', 'admin:admin-ability'])->group(function () {
    Route::post('/inject-system-events', [SystemEventController::class, 'create']);
    Route::post('/remove-system-events', [SystemEventController::class, 'create']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/inject-system-events', [SystemEventController::class, 'create']);
    Route::post('/remove-system-events', [SystemEventController::class, 'destroyAll']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/relatives', [RelativeController::class, 'index']);
    Route::get('/relatives/{id}', [RelativeController::class, 'show']);
    Route::post('/relatives', [RelativeController::class, 'store']);
    Route::put('/relatives/{id}', [RelativeController::class, 'update']);
    Route::delete('/relatives/{id}', [RelativeController::class, 'destroy']);
});