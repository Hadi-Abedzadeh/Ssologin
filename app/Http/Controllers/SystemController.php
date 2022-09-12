<?php

namespace Hadiabedzadeh\Ssologin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SystemController extends Controller
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


    public function sso(Request $request)
    {
        $token = $request->token;

        $response = Http::get('//inspect.gandomcs.com/service/v1/system-profile', ['token' => $token])->json();
        $email = isset($response['result']['userProfile']['email']) ? $response['result']['userProfile']['email'] : null;
        if(!is_null($email)){
            $email = DB::selectOne("SELECT * FROM users where email = :email", ['email'=> $email]);
        }else{
            return 'ss';
        }

        if(isset($email->id)) {
            \auth()->loginUsingId($email->id, true);
            return redirect()->route('dashboard.index');
        }
        return 'login failed';
    }

    public function profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:user_system',
        ]);

        if ($validator->fails()) {
            return $validator->validate();
        }


        $response = Http::get('inspect.gandomcs.com/service/v1/system-profile', ['token' => $validator->validated()['token']]);

        $userProfile = DB::selectOne("SELECT TOP 1 u.id as user_id, u.email, u.name, u.username,u.mobile, u.ldap_user, u.ssn FROM user_system us
        INNER JOIN users u ON u.id = us.user_id WHERE us.token = :token ORDER BY 1 DESC", ['token' => $validator->validated()['token']]);

        $roles = [];
        foreach(User::find($userProfile->user_id)->roles as $role) {
            $roles[$role->id] = $role->name;
        }

        $implodedRoleId = $this->addQouteImplode($role->id);

        $permissions = DB::select("SELECT name, guard_name, title, source, parent, created_at FROM permissions p INNER JOIN role_has_permissions rhp ON p.id = rhp.permission_id AND rhp.role_id IN ($implodedRoleId)");

        $permissions = collect($permissions)->map(function($permissions){
            $perm['name']       = $permissions->name;
            $perm['title']      = $permissions->title;
            $perm['guard_name'] = $permissions->guard_name;
            $perm['source']     = $permissions->source;
            $perm['parent']     = $permissions->parent;
            $perm['created_at'] = $permissions->created_at;
            return $perm;
        });

        unset($userProfile->user_id);

        return [
            'userProfile' => $userProfile,
            'roles'       => array_values($roles),
            'permission'  => $permissions
        ];

    }

    public function list()
    {
        $system_list = DB::select("SELECT * FROM system_list");
        return $system_list;
    }


    private function addQouteImplode($params)
    {
        $params = explode(',', $params);

        foreach ($params as $getTo) {
            $to[] = "'$getTo'";
        }
        return implode(', ', $to);

    }

}
