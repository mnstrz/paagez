<?php

namespace Monsterz\Paagez\Classes;

use Illuminate\Support\ServiceProvider;

class PaagezProvider extends ServiceProvider{

	public $namespace = '';

	public $path = '';

	public function boot()
	{
		$this->getNameSpace();
		$this->registerRoutes();
		$this->registerViews();
		$this->registerMigrations();
		$this->registerSeeders();
		$this->module_boot();
	}

	public function register()
	{
		$this->module_register();
	}

	/**
	 * will overides on child
	 * */
    public function module_boot()
    {

    }


	/**
	 * will overides on child
	 * */
    public function module_register()
    {
        
    }

	protected function getNameSpace()
	{
		$namespace = str_replace("ModuleServiceProvider","",get_class($this));
		$this->namespace = '\\'.$namespace;

		$module = $this->namespace.'Module';
		$module = new $module;
		$this->path = $module->path;
	}

	protected function registerRoutes()
	{
		$module = $this->namespace.'Module';
		$module = new $module;

		if(file_exists($this->path."routes/web.php"))
		{
	        \Route::middleware('web')->prefix($module->route_prefix)->as($module->route_name.".")->group(function () {
	        	$this->loadRoutesFrom($this->path."routes/web.php");
	        });
		}

		if(file_exists($this->path."routes/admin.php"))
		{
	        \Route::middleware('web','admin','role:admin')->prefix(config('paagez.prefix')."/".$module->route_prefix)->as(config('paagez.route_prefix').".".$module->route_name.".")->group(function () {
				\Route::get('/config', [$this->namespace.'Module', 'config'])->name('config');
				\Route::post('/config', [$this->namespace.'Module', 'update_config'])->name('config.submit');
	        	$this->loadRoutesFrom($this->path."routes/admin.php");
	        });
		}

		if(file_exists($this->path."routes/api.php"))
		{
	        \Route::middleware('auth:api')->prefix('api/')->as("api.")->group(function () {
	        	$this->loadRoutesFrom($this->path."routes/api.php");
	        });
		}

		$roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
		foreach ($roles as $key => $role) {
			if(file_exists($this->path."routes/{$role->name}.php"))
			{
		        \Route::middleware('web','admin',"role:{$role->name}")->prefix($module->route_prefix)->as($module->route_name.".")->group(function () use($role) {
		        	$this->loadRoutesFrom($this->path."routes/{$role->name}.php");
		        });
			}
		}
	}

	protected function registerViews()
	{
		$module = $this->namespace.'Module';
		$module = new $module;

		if(file_exists($this->path."resources/views"))
		{
			$this->loadViewsFrom($this->path."resources/views",$module->name);
		}
	}

	protected function registerMigrations()
	{
		$module = $this->namespace.'Module';
		$module = new $module;

		if(file_exists($this->path."database/migrations"))
		{
			$this->loadMigrationsFrom($this->path."database/migrations");
		}
	}

	protected function registerSeeders()
	{
		$module = $this->namespace.'Module';
		$module = new $module;

		if(file_exists($this->path."database/migrations"))
		{
			$this->loadMigrationsFrom($this->path."database/migrations");
		}
	}
}