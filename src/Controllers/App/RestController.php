<?php

namespace Monsterz\Paagez\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestController extends Controller
{
    public function index()
    {
        $datas = config('paagez.models.user')::whereHas('roles',function($q)
            {
                $q->where('name','user_api')->where('guard_name','api');
            });
        if(request()->name)
        {
            $datas->where("name","like","%".request()->name."%");
        }
        if(request()->email)
        {
            $datas->where("email","like","%".request()->email."%");
        }
        $datas = $datas->paginate(10);
        return view(config('paagez.theme').'::app.rest.index',compact('datas'));
    }

    public function create()
    {
        $rest = config('paagez.models.user');
        $data = new $rest;
        return view(config('paagez.theme')."::app.rest.form",compact("data"));
    }

    public function store(Request $request)
    {
        $rest = config('paagez.models.user');
        $rest = new $rest;
        $tablename = $rest->getTable();
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:'.$tablename.',email',
            'password' => 'required|string|max:255|min:8|confirmed'
        ],[],[
            'name' => __('Name'),
            'email' => __('Email Address'),
            'password' => __('Password')
        ]);
        $data = config('paagez.models.user')::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);
        $role = config('paagez.models.roles')::where('name','user_api')->where('guard_name','api')->first();
        if($role)
        {
            $data->guard_name = 'api';
            $data->assignRole($role->id);
        }
        return redirect()->route(config('paagez.route_prefix').'.app.rest.index')->with(["success" => __("Successfully create new client API")]);
    }

    public function revoke($rest)
    {
        $data = config('paagez.models.user')::where('id','!=',\Auth::user()->id)->findOrFail($rest);
        $data->guard_name = 'api';
        $data->assignRole([]);
        $data->delete();
        return redirect()->route(config('paagez.route_prefix').'.app.rest.index')->with(["success" => __("Successfully revoke client API")]);
    }
}
