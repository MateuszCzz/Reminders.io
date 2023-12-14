<?php

use App\Http\Controllers\SystemEventController;
use App\Http\Controllers\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RelativeController;
use App\Http\Controllers\NotificationController;
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
    Route::post('/inject-system-events', [SystemEventController::class, 'jsonInjection']);
    Route::post('/remove-system-events', [SystemEventController::class, 'destroyAll']);
});

Route::middleware(['auth:sanctum'])->group(function () {
//notification
    Route::put('nameDayForRelative/{relative_id}/{user_id}', [NotificationController::class, 'nameDayForRelative']);
    Route::post('notificationCreateFind/{userId}/{eventId}/{eventDate}', [NotificationController::class, 'createOrFindNotification']);
    Route::post('createNotificationsForUser/{userId}', [NotificationController::class, 'createNotificationsForUser']);

    //system events
    Route::get('/system-events', [SystemEventController::class, 'index']);
    Route::get('/system-events/non-custom', [SystemEventController::class, 'getNonCustomEvents']);
    Route::get('/system-events/{id}', [SystemEventController::class, 'show']);
    Route::post('/system-events', [SystemEventController::class, 'store']);
    Route::put('/system-events/{id}', [SystemEventController::class, 'update']);
    Route::delete('/system-events/{id}', [SystemEventController::class, 'destroy']);
//relatives
    Route::get('/relatives', [RelativeController::class, 'index']);
    Route::get('/relatives/{id}', [RelativeController::class, 'show']);
    Route::post('/relatives', [RelativeController::class, 'store']);
    Route::put('/relatives/{id}', [RelativeController::class, 'update']);
    Route::delete('/relatives/{id}', [RelativeController::class, 'destroy']);
});



