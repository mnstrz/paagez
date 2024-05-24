<?php

namespace Monsterz\Paagez\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        $datas = config('paagez.models.roles')::query();
        if(request()->name)
        {
            $datas->where("name","like","%".request()->name."%");
        }
        if(request()->guard)
        {
            $datas->where("guard","like","%".request()->guard."%");
        }
        $datas = $datas->paginate(10);
        return view(config('paagez.theme').'::app.roles.index',compact('datas'));
    }

    public function create()
    {
        $roles = config('paagez.models.roles');
        $data = new $roles;
        return view(config('paagez.theme')."::app.roles.form",compact("data"));
    }

    public function store(Request $request)
    {
        $roles = config('paagez.models.roles');
        $roles = new $roles;
        $tablename = $roles->getTable();
        $this->validate($request,[
            'name' => 'required|string|max:255|unique:'.$tablename.',name',
            'guard_name' => 'required|string|max:255'
        ],[],[
            'name' => __('Name'),
            'guard_name' => __('Guard'),
        ]);
        config('paagez.models.roles')::create([
            "name" => $request->name,
            "guard_name" => $request->guard_name
        ]);
        return redirect()->route(config('paagez.route_prefix').'.app.roles.index')->with(["success" => __("Successfully create new role")]);
    }

    public function edit($roles)
    {
        $data = config('paagez.models.roles')::findOrFail($roles);
        if($data->id == 1 || ($data->name == 'user' && $data->guard_name == 'api'))
        {
            return redirect()->route(config('paagez.route_prefix').'.app.roles.index')->with(["warning" => __("The roles cannot be modify or delete")]);
        }
        return view(config('paagez.theme')."::app.roles.form",compact("data"));
    }

    public function update(Request $request, $roles)
    {
        $data = config('paagez.models.roles')::findOrFail($roles);
        $roles = config('paagez.models.roles');
        $roles = new $roles;
        $tablename = $roles->getTable();
        $this->validate($request,[
            'name' => 'required|string|max:255|unique:'.$tablename.',name,'.$data->id,
            'guard_name' => 'required|string|max:255'
        ],[],[
            'name' => __('Name'),
            'guard_name' => __('Guard'),
        ]);
        $data->update([
            "name" => $request->name,
            "guard_name" => $request->guard_name
            
        ]);
        return redirect()->route(config('paagez.route_prefix').'.app.roles.index')->with(["success" => __("Successfully edited role")]);
    }

    public function destroy($roles)
    {
        $data = config('paagez.models.roles')::findOrFail($roles);
        if($data->id == 1 || ($data->name == 'user' && $data->guard_name == 'api'))
        {
            return redirect()->route(config('paagez.route_prefix').'.app.roles.index')->with(["warning" => __("The roles cannot be modify or delete")]);
        }
        $data->delete();
        return redirect()->route(config('paagez.route_prefix').'.app.roles.index')->with(["success" => __("Successfully deleted role")]);
    }
}
