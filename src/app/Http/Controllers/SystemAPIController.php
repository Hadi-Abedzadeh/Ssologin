<?php

namespace Hadiabedzadeh\Ssologin\app\Http\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SystemAPIController extends Controller
{
    public function token()
    {
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
    }

    public function ssoAuth(Request $request)
    {
        $token = request()->token;

        $user_system = DB::selectOne("SELECT TOP 1 * FROM user_system us INNER JOIN users u ON u.id = us.user_id WHERE us.token = :token order by 1 DESC", ['token' => $token]);

        $email = isset($user_system->email) ? $user_system->email : null;
        if (!is_null($email)) {
            $email = DB::selectOne("SELECT * FROM users where email = :email", ['email' => $email]);
        } else {
            return response()->json(['login failed']);
        }

        if(isset($email->id)) {
           \auth()->loginUsingId($email->id, true);
            return redirect()->route('dashboard.index');
        }

        return response()->json(['login failed']);
    }

    public function list()
    {
        $system_list = DB::select("SELECT * FROM system_list");
        return $system_list;
    }
}
