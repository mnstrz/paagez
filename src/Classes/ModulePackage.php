<?php

namespace Monsterz\Paagez\Classes;
use Monsterz\Paagez\Classes\Command;

class ModulePackage
{
	public function update($packages=[])
	{
		try {
			$updated = 0;
			foreach($packages as $name => $version)
			{
				if($this->checkVersion($name,$version,false))
				{
					Command::requirePackage($name,$version);
					config('paagez.models.module_packages')::updateOrCreate([
						'vendor' => $name
					],[
						'vendor' => $name,
						'version' => $version
					]);
					$updated++;
				}
			}
			return $updated;
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function checkVersion($name,$version,$exception=true)
	{
		$installed = config('paagez.models.module_packages')::where('vendor',$name)->first();
		if(!$installed)
		{
			return true;
		}
		if($version == "*")
		{
			return true;
		}
		if($installed->version == "*")
		{
			$installed->version = 0;
		}
		if(floatval($installed->version) > floatval($version))
		{
			if($exception)
			{
				throw new \Exception(__('paagez.package_more_update',['name'=>$name]), 1);
			}
			return false;
		}
		return true;
	}
}