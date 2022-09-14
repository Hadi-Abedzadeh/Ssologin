<?php

namespace Hadiabedzadeh\Ssologin\app\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\User;
use Hadiabedzadeh\Ssologin\app\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $systems = DB::select("SELECT * FROM system_list ORDER BY id DESC");

        return view('systems::index', compact('systems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('systems::create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'        => 'required|max:40',
            'title'       => 'required|max:40',
            'description' => 'required|max:40',
            'icon'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            'link'        => 'required|max:40',
        ]);

        $system_list = DB::insert("INSERT INTO system_list (name, title, description, icon, link, [key]) VALUES (:name, :title, :description, :icon, :link, :key) ", [
            'name'         => $request->name,
            'title'        => $request->title,
            'description'  => $request->description,
            'icon'         => $request->icon,
            'link'         => $request->link,
            'key'          =>  $this->generateRandomString()
        ]);

        if (isset($system_list)) {
            return redirect()->route('ssoSystem.index')->with('success', __('system::systems.Updated successfully'));
        } else {
            return redirect()->route('ssoSystem.index')->with('warning', __('system::systems.Your request failed'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $system_list = DB::selectOne("SELECT * FROM system_list WHERE id = :id", ['id' => $id]);
        $permissions = DB::select("SELECT * FROM permissions ORDER BY id DESC");


        return view('systems::.edit', compact('system_list', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'        => 'nullable|max:40',
            'title'       => 'nullable|max:40',
            'description' => 'nullable|max:40',
            'icon'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            'link'        => 'nullable|max:40',
        ]);

        $update = DB::update("UPDATE system_list set name = :name, title = :title, description = :description, icon = :icon, link = :link WHERE id = :id", [
            'name'        => $request->name,
            'title'       => $request->title,
            'description' => $request->description,
            'icon'        => $request->icon,
            'link'        => $request->link,
            'id'          => $id
        ]);

        if (isset($update)) {
            return redirect()->route('ssoSystem.index')->with('success', __('system::systems.Updated successfully'));
        } else {
            return redirect()->route('ssoSystem.index')->with('warning', __('system::systems.Your request failed'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
