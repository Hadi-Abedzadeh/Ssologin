<?php

use  \Hadiabedzadeh\Ssologin\App\Http\Controllers\SystemController;

Route::group([], function () {
    Route::get('/login/sso', [SystemController::class, 'sso']);
    Route::get('/system-profile',  [SystemController::class, 'profile']);
    Route::get('/system-list',  [SystemController::class, 'list']);
    Route::group(['middleware' => 'jwt.verify'], function ($router) {
        Route::get('/system-token',  [SystemController::class, 'token']);
    });
});

