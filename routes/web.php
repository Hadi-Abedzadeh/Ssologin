<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::group([], function () {
    Route::get('/login/sso', function(){

        $token = request()->token;

        $response = Http::get(config('app.sso_url'), ['token' => $token])->json();
        $email = isset($response['result']['userProfile']['email']) ? $response['result']['userProfile']['email'] : null;
        if(!is_null($email)){
            $email = DB::selectOne("SELECT * FROM users where email = :email", ['email'=> $email]);
        }else{
            return 'login failed';
        }

        if(isset($email->id)) {
            \auth()->loginUsingId($email->id, true);
            return redirect()->route('dashboard.index');
        }
        return 'login failed';
    });
    
    Route::get('/system-list', function (){
        $system_list = DB::select("SELECT * FROM system_list");
        return $system_list;
    });
    
    Route::group(['middleware' => 'jwt.verify'], function ($router) {
        Route::get('/system-token',  function (){

            $system_id = (int)\request()->system_id;
            $user_id   = auth()->guard('api')->id();

            $created_token = hash('sha256', $system_id.$user_id. microtime(true));


            $userSystem = new UserSystem;
            $userSystem->user_id   = $user_id;
            $userSystem->system_id   = $system_id;
            $userSystem->token     = $created_token;
            $userSystem->save();

            return [
                'user_id'   => $user_id,
                'system_id' => $system_id,
                'token'     => $created_token
            ];
        });
    });
});

