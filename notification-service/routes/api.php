<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

Route::middleware('api.key')->group(function () {

    Route::post('/notifications/send', [
        NotificationController::class,
        'send'
    ]);

    Route::post('/notifications/send-bulk', [
        NotificationController::class,
        'sendBulk'
    ]);

    Route::get('/notifications/{userId}', [
        NotificationController::class,
        'getByUser'
    ]);

    Route::post('/notifications/read', [
        NotificationController::class,
        'markRead'
    ]);
});

