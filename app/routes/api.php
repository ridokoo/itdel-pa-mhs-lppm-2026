<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:req-limit'])->group(function () {
    Route::group(['middleware' => 'api.check.auth'], function () {});
});



