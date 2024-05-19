<?php

namespace Monsterz\Paagez\Controllers\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function index()
    {
        if(!\Schema::hasTable(config('paagez.db_prefix').'config'))
        {
            \Artisan::call('migrate');
        }
        if(config('paagez.models.config')::first() && config('paagez.models.user')::first())
        {
            return redirect()->route(config('paagez.route_prefix').".index");
        }
        return view(config('paagez.theme').'::setup.index');
    }

    public function install(Request $request)
    {
        if(!\Schema::hasTable(config('paagez.db_prefix').'config'))
        {
            \Artisan::call('migrate');
        }
        if(config('paagez.models.config')::first() && config('paagez.models.user')::first())
        {
            return redirect()->route(config('paagez.route_prefix').".index");
        }
        $this->validate($request,[
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|max:2000',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ],[],[
            'app_name' => __('paagez.app_name'),
            'app_logo' => __('paagez.app_logo'),
            'name' => __('Username'),
            'password' => __('Password'),
            'email' => __('Email Address'),
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
        $user = config('paagez.models.user')::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->assignRole(['admin']);
        \Auth::login($user);
        return redirect()->route(config('paagez.route_prefix').".index")->with(['success' => __('paagez.install_success')]);
    }

    public function update()
    {
        $updates_app = [];
        $install = true;
        $modules = glob(base_path('app/Modules/*/Module.php'));
        foreach ($modules as $key => $module) {
            try {
                $path = explode("/", $module);
                $class_name = array_slice($path, -2, 1, true);
                $class_name = array_values($class_name)[0];
                $class_name = '\\App\\Modules\\'.$class_name.'\\Module';
                $module = new $class_name;
                if($module->need_update)
                {
                    array_push($updates_app,[
                        'name' => $module->name,
                        'title' => $module->title,
                        'version' => $module->version,
                        'latest_version' => $module->latest_version,
                        'namespace' => $module->namespace,
                        'can_install' => $module->can_install,
                        'error_messages' => $module->error_messages,
                        'warning_messages' => $module->warning_messages,
                        'installed' => $module->installed,
                    ]);
                    if(!$module->can_install)
                    {
                        $install = false;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }
        return view(config('paagez.theme').'::setup.update',compact('updates_app','install'));
    }

    public function updatePackage(Request $request)
    {
        sleep(2);
        try {
            $this->validate($request,[
                'module_id' => 'required'
            ]);
            \Artisan::call('paagez:package-update',[
                '--module' => $request->module_id
            ]);
            return response()->json([
                'message' => 'Sukses memperbarui package'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],422);
        }
    }

    public function updateDatabase(Request $request)
    {
        sleep(2);
        try {
            $this->validate($request,[
                'module_id' => 'required'
            ]);
            \Artisan::call('paagez:db-update',[
                '--module' => $request->module_id
            ]);
            return response()->json([
                'message' => 'Sukses memperbarui database'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],422);
        }
    }

    public function updateModule(Request $request)
    {
        sleep(2);
        try {
            $this->validate($request,[
                'module_id' => 'required'
            ]);
            \Artisan::call('paagez:theme-assets',[
                '--module' => $request->module_id
            ]);
            \Artisan::call('paagez:artisan-module',[
                '--module' => $request->module_id
            ]);
            \Artisan::call('paagez:version-update',[
                '--module' => $request->module_id
            ]);
            return response()->json([
                'message' => 'Sukses memperbarui module'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],422);
        }
    }
}
