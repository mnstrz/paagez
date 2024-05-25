<?php

namespace Monsterz\Paagez\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users_class = config('paagez.models.roles');
        $users_class = new $users_class;
        $user_table = $users_class->getTable();

        $datas = config('paagez.models.user')::query()->whereHas('roles',function($q)
            {
                $q->where('name','!=','user_api');
            });
        if(request()->name)
        {
            $datas->where("name","like","%".request()->name."%");
        }
        if(request()->email)
        {
            $datas->where("email","like","%".request()->email."%");
        }
        if(request()->roles)
        {
            $datas->whereHas('roles',function($q)
            {
                $q->whereIn('id',request()->roles??[]);
            });
        }
        $datas = $datas->paginate(10);
        $roles = config('paagez.models.roles')::where('guard_name','web')->get();
        return view(config('paagez.theme').'::config.users.index',compact('datas','roles'));
    }

    public function create()
    {
        $users = config('paagez.models.user');
        $data = new $users;
        $roles = config('paagez.models.roles')::where('guard_name','web')->get();
        return view(config('paagez.theme')."::config.users.form",compact("data",'roles'));
    }

    public function store(Request $request)
    {
        $users = config('paagez.models.user');
        $users = new $users;
        $tablename = $users->getTable();
        $roles = config('paagez.models.roles')::all()->pluck('name')->toArray();
        $roles = implode(",",$roles);
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:'.$tablename.',email',
            'roles' => 'required',
            'roles.*' => 'nullable|in:'.$roles,
            'password' => 'required|string|max:255|min:8|confirmed'
        ],[],[
            'name' => __('Name'),
            'email' => __('Email Address'),
            'password' => __('Password'),
            'roles' => __('Roles'),
            'roles.*' => __('Role'),
        ]);
        $data = config('paagez.models.user')::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);
        $data->assignRole($request->roles??[]);
        return redirect()->route(config('paagez.route_prefix').'.config.users.index')->with(["success" => __("Successfully create new user")]);
    }

    public function edit($users)
    {
        $data = config('paagez.models.user')::where('id','!=',\Auth::user()->id)->findOrFail($users);
        $roles = config('paagez.models.roles')::where('guard_name','web')->get();
        return view(config('paagez.theme')."::config.users.form",compact("data",'roles'));
    }

    public function update(Request $request, $users)
    {
        $data = config('paagez.models.user')::where('id','!=',\Auth::user()->id)->findOrFail($users);
        $users = config('paagez.models.user');
        $users = new $users;
        $tablename = $users->getTable();
        $roles = config('paagez.models.roles')::all()->pluck('name')->toArray();
        $roles = implode(",",$roles);
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:'.$tablename.',email,'.$data->id,
            'roles' => 'required',
            'roles.*' => 'nullable|in:'.$roles,
            'password' => 'nullable|string|max:255|min:8|confirmed'
        ],[],[
            'name' => __('Name'),
            'email' => __('Email Address'),
            'password' => __('Password'),
            'roles' => __('Roles'),
            'roles.*' => __('Role'),
        ]);
        $data->update([
            "name" => $request->name,
            "email" => $request->email
        ]);
        if($request->password)
        {
            $data->password = bcrypt($request->password);
            $data->save();
        }
        $data->assignRole($request->roles??[]);
        return redirect()->route(config('paagez.route_prefix').'.config.users.index')->with(["success" => __("Successfully edited user")]);
    }

    public function destroy($users)
    {
        $data = config('paagez.models.user')::where('id','!=',\Auth::user()->id)->findOrFail($users);
        $data->assignRole([]);
        $data->delete();
        return redirect()->route(config('paagez.route_prefix').'.config.users.index')->with(["success" => __("Successfully deleted role")]);
    }
}
