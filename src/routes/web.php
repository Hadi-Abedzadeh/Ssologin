<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use \Hadiabedzadeh\Ssologin\app\Http\Controllers\SystemAPIController;
use \Hadiabedzadeh\Ssologin\app\Http\Controllers\Dashboard\SystemController;

Route::group(['prefix' => config('sso.admin_prefix')], function () {
    Route::resource('dashboard/ssoSystem', SystemController::class);
});

Route::group(['prefix'=> config('sso.api_prefix')], function () {
    Route::get('/login/sso', [SystemAPIController::class, 'sso']);
    Route::get('/system-list', [SystemAPIController::class, 'list']);
    Route::group(['middleware' => 'jwt.verify'], function ($router) {
        Route::get('/system-token',  [SystemAPIController::class, 'token']);
    });
});
