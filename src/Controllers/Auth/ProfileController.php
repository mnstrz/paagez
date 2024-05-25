<?php

namespace Monsterz\Paagez\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function index()
	{
		$data = config('paagez.models.user')::find(\Auth::user()->id);
        return view(config('paagez.theme')."::auth.profile",compact("data"));
	}

	public function update(Request $request)
	{
		$data = config('paagez.models.user')::find(\Auth::user()->id);
        $users = config('paagez.models.user');
        $users = new $users;
        $tablename = $users->getTable();
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:'.$tablename.',email,'.$data->id,
        ],[],[
            'name' => __('Name'),
            'email' => __('Email Address'),
            'password' => __('Password'),
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
        return redirect()->route(config('paagez.route_prefix').'.profile')->with(["success" => __("Successfully updated your profile")]);
	}
}