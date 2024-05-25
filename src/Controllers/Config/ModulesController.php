<?php

namespace Monsterz\Paagez\Controllers\Config;

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
        return view(config('paagez.theme').'::config.modules.index',compact('not_installed','installed'));
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
        return view(config('paagez.theme').'::config.modules.show',compact('module','moduleclass'));
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
