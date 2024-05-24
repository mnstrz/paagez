<?php

namespace Monsterz\Paagez\Classes\Navigations;

class ModuleMenu
{
	public $menu = [];

	public function __construct()
	{
		$this->register();
	}

	public function register()
	{

	}

	public function add_menu($menu)
	{
		$name = (isset($menu['name']) && $menu['name']) ? $menu['name'] : null;
		$label = (isset($menu['label']) && $menu['label']) ? $menu['label'] : null;
		$icon = (isset($menu['icon']) && $menu['icon']) ? $menu['icon'] : null;
		$image = (isset($menu['image']) && $menu['image']) ? $menu['image'] : null;
		$url = (isset($menu['url']) && $menu['url']) ? $menu['url'] : null;
		$parent = (isset($menu['parent']) && $menu['parent']) ? $menu['parent'] : null;
		$order = (isset($menu['order']) && $menu['order']) ? (int) $menu['order'] : 99999999;
		$roles = (isset($menu['roles']) && $menu['roles'] && is_array($menu['roles'])) ? $menu['roles'] : [];
		if($name && $label && $url)
		{
			$this->menu[] = [
				'order' => $order,
				'name' => $name,
				'parent' => $parent,
				'label' => $label,
				'url' => $url,
				'icon' => $icon,
				'image' => $image,
				'roles' => $roles
			];
		}
	}
}