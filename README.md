---
## Installation & Usage

> **Requires [PHP 7.4+](https://php.net/releases/)**

Require SSO login using [Composer](https://getcomposer.org):

```bash
composer require hadiabedzadeh/ssologin
```

Add sso url to `config/app.php` => 'sso_url' => 'https://www.gandomcs.com/sso' 

## Vendor publishe
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Hadiabedzadeh\Ssologin\SsoLoginServiceProvider"
```

You need to publish vendor in your project

## Contributing
Thank you for considering to contribute to all the contribution

You can have a look at the [CHANGELOG](CHANGELOG.md) for constant updates & detailed information about the changes.

## License 
SSO login is an open-sourced software licensed under the [MIT license](LICENSE.md).

### Client
```php
  public function sso(Request $request)
    {
        $token = $request->token;

        $response = Http::get('inspect.gandomcs.com/service/v1/system-profile', ['token' => $token])->json();
        $email = isset($response['result']['userProfile']['email']) ? $response['result']['userProfile']['email'] : null;
        if (!is_null($email)) {
            $email = DB::selectOne("SELECT * FROM users where email = :email", ['email' => $email]);
        } else {
            return self::response('User not found', Response::HTTP_NOT_FOUND);
        }

        if(isset($email->id)) {
            $user = User::find($email->id);
            $token = auth()->guard('api')->login($user);

            return self::response([
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => (auth()->guard('api')->factory()->getTTL() * 60) * 10,
                'user'         => auth()->guard('api')->user(),
                'roles'        => auth()->guard('api')->user()->getRoleNames(),
                'permissions'  => auth()->guard('api')->user()->getPermissionsViaRoles()->pluck('name'),
            ], \Illuminate\Http\Response::HTTP_OK);
        }

        return Controller::response('login failed', 200);
    }
```

routes.api.php
### Server
```php
    Route::group([], function () {
        Route::get('/system-profile',  [SystemController::class, 'profile']);
        Route::get('/system-list',  [SystemController::class, 'list']);
        Route::group(['middleware' => 'jwt.verify'], function ($router) {
            Route::get('/system-token',  [SystemController::class, 'token']);
        });
    });
```
  
### Server Controller

```php
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\Helper;
use App\Models\User;
use App\Models\UserSystem;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
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

        return Controller::response([
            'user_id'   => $user_id,
            'system_id' => $system_id,
            'token'     => $created_token
        ], Response::HTTP_OK);
    }

    public function profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|exists:user_system',
        ]);

        if ($validator->fails()) {
            return Controller::response($validator->validate(), 200);
        }

        $userProfile = DB::selectOne("SELECT TOP 1 u.id as user_id, u.email, u.name, u.username,u.mobile, u.ldap_user, u.ssn FROM user_system us
        INNER JOIN users u ON u.id = us.user_id WHERE us.token = :token ORDER BY 1 DESC", ['token' => $validator->validated()['token']]);

        $roles = [];
        foreach(User::find($userProfile->user_id)->roles as $role) {
            $roles[$role->id] = $role->name;
        }

        $implodedRoleId = Helper::addQouteImplode($role->id);

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

        return Controller::response([
            'userProfile' => $userProfile,
            'roles'       => array_values($roles),
            'permission'  => $permissions
        ],200);
    }

    public function list()
    {
        $system_list = DB::select("SELECT sl.id, sl.name, sl.title,sl.link, sl.icon, IIF(um.menu_id IS null, null, 1) as active
        FROM system_list sl LEFT JOIN user_menu um ON um.menu_id = sl.id
        WHERE um.menu_id IS NOT null");

        return Controller::response($system_list, Response::HTTP_OK);
    }
}
```
