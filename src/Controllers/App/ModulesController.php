<?php

namespace Monsterz\Paagez\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModulesController extends Controller
{
    public function index()
    {
        $modules = $this->getModules();
        $not_installed = \collect($modules)->filter(function ($item) {
                        return !$item->installed;
                    })->sortBy('name')??[];
        $installed = \collect($modules)->filter(function ($item) {
                        return $item->installed;
                    })->sortByDesc('need_update')->sortBy('name')??[];
        return view(config('paagez.theme').'::app.modules.index',compact('not_installed','installed'));
    }

    protected function getModules()
    {
        $availabe = [];
        $install = true;
        $modules = glob(base_path('app/Modules/*/Module.php'));
        foreach ($modules as $key => $module) {
            try {
                $path = explode("/", $module);
                $class_name = array_slice($path, -2, 1, true);
                $class_name = array_values($class_name)[0];
                $class_name = '\\App\\Modules\\'.$class_name.'\\Module';
                $module = new $class_name;
                array_push($availabe,$module);
            } catch (\Exception $e) {
                continue;
            }
        }
        return $availabe;
    }

    public function show($module)
    {
        $module = config('paagez.models.module')::where('name',$module)->firstOrFail();
        $moduleclass = $module->namespace."\\Module";
        $moduleclass = new $moduleclass;
        return view(config('paagez.theme').'::app.modules.show',compact('module','moduleclass'));
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
        return redirect(config('paagez.prefix')."/app/config")->with(["success" => __("Updated")]);
    }

    public function changeStatus($module)
    {
        try {
            $module = config('paagez.models.module')::where('name',$module)->firstOrFail();
            $module->update([
                'is_active' => !$module->is_active
            ]);
            return response()->json([
                'status' => 'success'
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],422);
        }
    }
}
