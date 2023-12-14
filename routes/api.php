<?php

use App\Http\Controllers\SystemEventController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('/remove-system-events', [SystemEventController::class, 'destroyAll']);
});