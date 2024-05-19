<?php

namespace Monsterz\Paagez\Classes;

class ModuleConfig
{
	public static function update($name,$config=[],$value=null)
	{
		$module = config('paagez.models.module')::where('name',$name)->first();
		if(!$module)
		{
			return null;
		}
		if(is_array($config))
		{
			foreach ($config as $name => $value) {
				if(preg_match('/^[A-Za-z0-9]+$/',$name))
				{
					config('paagez.models.module_config')::updateOrCreate([
						'module_id' => $module->id,
						'name' => $name
					],[
						'module_id' => $module->id,
						'name' => $name,
						'value' => $value
					]);
				}
			}
			$config = new \StdClass;
			foreach(config('paagez.models.module_config')::where('module_id',$module->id)->get() as $cf)
			{
				$config->{$cf->name} = $cf->value;
			}
			return $config;
		}else{
			$config = config('paagez.models.module_config')::updateOrCreate([
				'module_id' => $module->id,
				'name' => $config
			],[
				'module_id' => $module->id,
				'name' => $config,
				'value' => $value
			]);
			return $config->pluck('value','name');
		}
	}

	public static function get($name,$field=false)
	{
		$module = config('paagez.models.module')::where('name',$name)->first();
		if(!$module)
		{
			return null;
		}
		if($field)
		{
			return config('paagez.models.module_config')::where('module_id',$module->id)->where('name',$field)->first()?->value;
		}else{
			$config = new \StdClass;
			foreach(config('paagez.models.module_config')::where('module_id',$module->id)->get() as $cf)
			{
				$config->{$cf->name} = $cf->value;
			}
			return $config;
		}
	}
}