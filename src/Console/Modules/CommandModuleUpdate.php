<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

use Monsterz\Paagez\Classes\Modules\ModulePackage;

class CommandModuleUpdate extends Command
{
    use TraitModule;

	protected $signature = 'paagez:update-module {--module=} {--all}';
	
	protected $description = 'Update module';

	protected $directory = '';

    protected $modules = [];

	public function handle()
    {
        if(!$this->option('module') && !$this->option('all'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create model\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>--module</>         Module name\n");
            $this->line("-----------------------------------\n");
            $module = $this->ask('Please enter the module name');
            $this->input->setOption('module', $module);
        }
        if(!$this->option('all'))
        {
            if($this->initials($this->option('module')))
            {
                return 0;
            }
            if($this->strings($this->option('module')))
            {
                return 0;
            }
            if($this->singleModule())
            {
                return 0;
            }
        }else{
            $this->allModules();
        }
        return 0;
    }

    public function singleModule()
    {
        try {
            if($this->installModule($this->namespace))
            {
                return 0;
            }
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }

    public function allModules()
    {
        $this->line("\nGetting updated modules...\n");
        $to_install = [];
        $install = true;
        $modules = glob(base_path('app/Modules/*/Module.php'));
        foreach ($modules as $key => $module) {
            $path = explode("/", $module);
            $class_name = array_slice($path, -2, 1, true);
            $class_name = array_values($class_name)[0];
            $class_name = '\\App\\Modules\\'.$class_name.'\\Module';
            $module = new $class_name;
            if($module->installed && $module->can_install && $module->need_update)
            {
                array_push($to_install,[
                    'name' => $module->name,
                    'title' => $module->title,
                    'version' => $module->version,
                    'latest_version' => $module->latest_version,
                    'namespace' => $module->namespace,
                    'can_install' => $module->can_install,
                    'error_messages' => $module->error_messages,
                    'warning_messages' => $module->warning_messages,
                ]);
            }
        }
        $count_modules = count($to_install);
        if($count_modules > 0)
        {
            $success = 0;
            $this->line("Found <fg=yellow>{$count_modules}</>...\n");
            foreach($to_install as $item)
            {
                $this->line("\n--- <fg=yellow>module\\$item[name]</> $item[title]\n");
            }
            $this->line("\Updating modules...\n");
            foreach($to_install as $item)
            {
                $success += $this->installModule($item['namespace']);
            }
            $this->line("Successfully update <fg=yellow>{$success}</> modules...\n");
        }else{
            $this->line("\nFound <fg=yellow>{$count_modules}</>...\n");
        }
    }

    public function installModule($namespace)
    {
        $classname = $namespace.'\Module';
        if(!class_exists($classname))
        {
            $this->line("\n<fg=yellow>module\\$this->module_name</> is not exists, please init first using <fg=yellow>php artisan paagez:module --module=$this->module_name --init</>\n");
            return 1;
        }
        $this->call('optimize:clear');
        $module = new $classname;
        if(count($module->packages) > 0)
        {
            $this->call('paagez:package-update',[
                '--module' => $module->name
            ]);
        }
        if(file_exists($module->path."/database/migrations"))
        {
            $this->call('paagez:db-update',[
                '--module' => $module->name
            ]);
        }
        if(count($module->artisan_call) > 0){
            $this->call('paagez:artisan-module',[
                '--module' => $module->name
            ]);
        }
        if(file_exists($module->path."/assets") && !file_exists(public_path('module/'.$module->name)))
        {
            $this->call('paagez:theme-assets',[
                '--module' => $module->name
            ]);
        }
        $this->line("\Updating <fg=yellow>module\\$module->name</> on version <fg=yellow>module\\$module->version</>...\n");
        $this->call('paagez:version-update',[
            '--module' => $module->name
        ]);
        $this->info("<fg=yellow>module\\$module->module_name</> ..................................</> Updating success\n");
        $this->call('optimize:clear');
        return 1;
    }
}