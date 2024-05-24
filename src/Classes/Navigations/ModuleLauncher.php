<?php

namespace Monsterz\Paagez\Classes\Navigations;

class ModuleLauncher
{
	public $launchers = [];

	public function __construct()
	{
		$this->register();
	}

	public function register()
	{

	}

	public function add_launcher($launchers)
	{
		$name = (isset($launchers['name']) && $launchers['name']) ? $launchers['name'] : null;
		$label = (isset($launchers['label']) && $launchers['label']) ? $launchers['label'] : null;
		$icon = (isset($launchers['icon']) && $launchers['icon']) ? $launchers['icon'] : null;
		$image = (isset($launchers['image']) && $launchers['image']) ? $launchers['image'] : null;
		$url = (isset($launchers['url']) && $launchers['url']) ? $launchers['url'] : null;
		$order = (isset($launchers['order']) && $launchers['order']) ? (int) $launchers['order'] : 99999999;
		$roles = (isset($launchers['roles']) && $launchers['roles'] && is_array($launchers['roles'])) ? $launchers['roles'] : [];
		if($name && $label && $url)
		{
			$this->launchers[] = [
				'order' => $order,
				'name' => $name,
				'label' => $label,
				'url' => $url,
				'icon' => $icon,
				'image' => $image,
				'roles' => $roles,
			];
		}
	}
}