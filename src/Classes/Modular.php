<?php

namespace Monsterz\Paagez\Classes;

class Modular
{
	public $name = '';

	public $title = '';

	public $publisher = '';

	public $email_publisher = '';

	public $website_publisher = '';

	public $version = 0;

	public $route_prefix = '';

	public $route_name = '';

	public $logo = '';

	public $latest_version = 0;

	public $packages = [];
	/**
	 * 
	 * [ 
	 * 	['foo/bar'=>'version']
	 * ]
	 * */

	public $artisan_call = [];
	/**
	 * 
	 * [ 
	 * 	['foo'=>['bar'=>'baz']]
	 * ]
	 * */

	public $namespace = '';

	public $need_update = false;

	public $can_install = true;

	public $error_messages = [];

	public $warning_messages = [];

	public $path = '';

	public $seeders = [];

	public $installed = false;

	public function __construct()
	{
		$this->getNameSpace();
		$this->getPath();
		$this->latestVersion();
		$this->isNeedUpdate();
		$this->isCanInstall();
		$this->checkPackageVersion();
		$this->update();
		$this->register();
	}

	public function latestVersion()
	{
		$module = config('paagez.models.module')::where('name',$this->name)->first();

		if($module)
		{
			$this->installed = true;
			$this->latest_version = $module->version;
		}
	}

	public function isNeedUpdate()
	{
		if($this->version > $this->latest_version)
		{
			$this->need_update = true;
		}
	}

	public function getNameSpace()
	{
		$class_name = get_class($this);
		$reflection_class = new \ReflectionClass($class_name);
		$this->namespace = '\\'.$reflection_class->getNamespaceName();
	}

	public function getPath()
	{
		$reflector = new \ReflectionClass($this->namespace."\Module");
		$this->path = str_replace("Module.php","",$reflector->getFileName());
	}

	public function isCanInstall()
	{
		if(!$this->name)
		{
			$this->can_install = false;
			$this->error_messages[] = __('paagez.module_name_required');
		}
		if(in_array($this->name,['admin','website','config','settings','users','user','role','permission','permissions','api']))
		{
			$this->can_install = false;
			$this->error_messages[] = __('paagez.invalid_name');
		}
		if(!$this->title)
		{
			$this->can_install = false;
			$this->error_messages[] = __('paagez.module_title_required');
		}
		if(!$this->publisher)
		{
			$this->can_install = false;
			$this->error_messages[] =  __('paagez.module_publisher_required');
		}
		if(!preg_match('/^[A-Za-z0-9_-]+$/',$this->name))
		{
			$this->can_install = false;
			$this->error_messages[] = __('paagez.module_name_char');
		}
		if($this->version < $this->latest_version)
		{
			$this->can_install = false;
			$this->error_messages[] = __('paagez.older_version');
		}
		foreach ($this->packages as $key => $package) {
			if (!$this->isValidPackageName($package['name']) || !$this->isValidPackageVersion($package['version'])) {
				$this->can_install = false;
				$this->error_messages[] = __('paagez.invalid_package');
	        }
		}
	}

    public function isValidPackageName($package)
    {
        return preg_match('/^[a-z0-9\-\/]+$/', $package);
    }

    public function isValidPackageVersion($version)
    {
        return preg_match('/^[a-z0-9\.\-\*]+$/', $version);
    }

    public function checkPackageVersion()
    {
    	foreach($this->packages as $package)
    	{
    		try{
    			$packageInstall = new PackageUpdate;
    			$packageInstall->checkVersion($package['name'],$package['version']);
    		} catch (\Exception $e)
    		{
    			$this->warning_messages[] = $e->getMessage();
    		}
    	}
    }

	public function config()
	{
		return view("paagez::debug.config",['namespace' => $this->namespace]);

		/**
		 * 
		$config = ModuleConfig::get($this->name);
		return view($this->name.'::config',compact('config'));
		 * */
	}

	public function update_config()
	{
		return view("paagez::debug.config",['namespace' => $this->namespace]);

		/**
		 * 
		$request = request()->except('_token');
		$validator = Validator::make($request, [

        ]);
        if ($validator->fails()) {
            return redirect()
            			->back()
                        ->withErrors($validator)
                        ->withInput();
        }
		ModuleConfig::update($this->name,$request);
        return redirect()->back()->with(['success' => __('paagez.config_updated',['name'=>$this->title])]);
		 * */
	}

	public function register()
	{
		
	}

	public function update()
	{
		
	}
}