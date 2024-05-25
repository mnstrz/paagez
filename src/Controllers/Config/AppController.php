<?php

namespace Monsterz\Paagez\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        return view(config('paagez.theme').'::config.app');
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|max:2000',
            'prefix' => 'required|string|max:20',
        ],[],[
            'app_name' => __('paagez.app_name'),
            'app_logo' => __('paagez.app_logo'),
            'prefix' => __('paagez.prefix'),
            'pwa' => __('paagez.enable_pwa'),
            'gcaptcha' => __('paagez.enable_gcaptcha'),
            'analytics' => __('paagez.analytics')
        ]);
        config('paagez.models.config')::updateOrCreate([
            'name' => 'app_name'
        ],[
            'name' => 'app_name',
            'value' => $request->app_name
        ]);
        if($request->has('app_logo')){
            $app_logo = $request->file('app_logo')->storeAs(
                'public/image', uniqid().".".$request->file('app_logo')->getClientOriginalExtension()
            );
            config('paagez.models.config')::updateOrCreate([
                'name' => 'app_logo'
            ],[
                'name' => 'app_logo',
                'value' => $app_logo
            ]);
        }
        config('paagez.models.config')::updateOrCreate([
            'name' => 'gcaptcha'
        ],[
            'name' => 'gcaptcha',
            'value' => $request->gcaptcha
        ]);
        config('paagez.models.config')::updateOrCreate([
            'name' => 'pwa'
        ],[
            'name' => 'pwa',
            'value' => $request->pwa
        ]);
        config('paagez.models.config')::updateOrCreate([
            'name' => 'analytics'
        ],[
            'name' => 'analytics',
            'value' => $request->analytics
        ]);
        $prefix = config('paagez.models.config')::updateOrCreate([
            'name' => 'prefix'
        ],[
            'name' => 'prefix',
            'value' => $request->prefix
        ]);
        \Config::set('paagez.prefix',$request->prefix);
        return redirect(config('paagez.prefix')."/config/app")->with(["success" => __("Updated")]);
    }
}
