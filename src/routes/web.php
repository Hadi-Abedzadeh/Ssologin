<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use \Hadiabedzadeh\Ssologin\app\Http\Controllers\SystemController;

Route::group([], function () {
    Route::get('/login/sso', [SystemController::class, 'sso']);
    Route::get('/system-list', [SystemController::class, 'list']);
    Route::group(['middleware' => 'jwt.verify'], function ($router) {
        Route::get('/system-token',  [SystemController::class, 'token']);
    });
});

