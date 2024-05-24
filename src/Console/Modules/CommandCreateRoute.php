<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateRoute extends Command
{
    use TraitModule;

	protected $signature = 'paagez:routes {--module=} {--all} {--web} {--api} {--admin}';
	
	protected $description = 'Create routes';

	protected $directory = '';

    protected $routes = [];

    public function __construct()
    {

        $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
        foreach ($roles as $key => $role) {
            $this->signature .= " {--$role->name}";
        }

        parent::__construct();
    }

	public function handle()
    {

        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create routes\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>--module</>     Module name\n");
            $this->line("<fg=yellow>--all</>        All routes\n");
            $this->line("<fg=yellow>--web</>        Using middleware ['web']\n");
            $this->line("<fg=yellow>--api</>        Using middleware ['api']\n");
            $this->line("<fg=yellow>--admin</>      Using middleware ['web',admin','role:admin']\n");
            $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
            foreach ($roles as $key => $role) {
            $this->line("<fg=yellow>--$role->name</>        Using middleware ['web',admin','role:$role->name']\n");
            }
            $this->line("-----------------------------------\n");
            $module = $this->ask('Please enter the module name');
            $this->input->setOption('module', $module);
            $this->inputRoute();
        }
        if($this->initials($this->option('module')))
        {
            return 0;
        }
        if($this->strings($this->option('module')))
        {
            return 0;
        }
        if($this->getRoutes())
        {
            return 0;
        }
        if($this->makeFile())
        {
            return 0;
        }
    }

    public function inputRoute($value=null)
    {
        $routes = ['all','web','admin','api'];
        $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
        foreach ($roles as $key => $role) {
            $routes[] = $role->name;
        } 
        $route = $this->ask('Please enter the route type <fg=yellow>['.implode(",",$routes).']</>');
        if(!$route || !$this->hasOption($route))
        {
            $this->comment("At least please choose one of route type\n");
            return $this->inputRoute($route);
        }else{
            if(!in_array($route,$routes))
            {
                $this->comment("At least please choose one of route type\n");
                return $this->inputRoute($route);
            }else{
                $this->input->setOption($route,1);
                return $route;
            }
        }
    }

    public function getRoutes()
    {
        $routes = [];
        if($this->option('all'))
        {
            $routes = ['web','admin','api'];
            $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
            foreach ($roles as $key => $role) {
                $routes[] = $role->name;
            } 
            $this->routes = $routes;
            return 0;
        }
        if($this->option('web'))
        {
            $routes[] = 'web';
        }
        if($this->option('api'))
        {
            $routes[] = 'api';
        }
        if($this->option('admin'))
        {
            $routes[] = 'admin';
        }
        $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
        foreach ($roles as $key => $role) {
            if($this->option($role->name))
            {
                $routes[] = $role->name;
            }
        }
        if(!$routes)
        {
            $this->comment("At least please choose one of route type\n");
            $this->inputRoute();
            $this->getRoutes();
            return 0;
        }
        $this->routes = $routes;
        return 0;
    }

    public function makeFile()
    {
        $this->line("\nCreating routes...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/routes")) {
                \File::makeDirectory($this->module_path."/routes", 0755, true);
            }
            foreach($this->routes as $route)
            {
                $filePath = $this->module_path . "/routes/{$route}.php";
                if(!file_exists($filePath))
                {
                    if($this->option('api'))
                    {
                        $fileContent = '<?php

Route::prefix("'.$this->module_name.'")->as("'.$this->module_name.'.")->group(function()
{
    // Route::resource("/",'.$this->namespace.'\HomeController::class)->parameters([""=>"'.$this->module_name.'"]);
});';
                    }else{
                        $fileContent = '<?php

// Route::resource("/",'.$this->namespace.'\HomeController::class)->parameters([""=>"'.$this->module_name.'"]);';
                    }
                    \File::put($filePath, $fileContent);
                    $this->info("$filePath ........................................... Success\n");
                }else{
                    $this->comment("$filePath ........................................... Already exists\n");
                }
            }
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}