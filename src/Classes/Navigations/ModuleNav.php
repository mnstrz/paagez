<?php

namespace Monsterz\Paagez\Classes\Navigations;

class ModuleNav
{
	public $navs = [];

	public function __construct()
	{
		$this->register();
	}

	public function register()
	{

	}

	public function add_nav($nav)
	{
		$name = (isset($nav['name']) && $nav['name']) ? $nav['name'] : null;
		$label = (isset($nav['label']) && $nav['label']) ? $nav['label'] : null;
		$icon = (isset($nav['icon']) && $nav['icon']) ? $nav['icon'] : null;
		$image = (isset($nav['image']) && $nav['image']) ? $nav['image'] : null;
		$url = (isset($nav['url']) && $nav['url']) ? $nav['url'] : null;
		$parent = (isset($nav['parent']) && $nav['parent']) ? $nav['parent'] : null;
		$order = (isset($nav['order']) && $nav['order']) ? (int) $nav['order'] : 99999999;
		if($name && ($label || $icon || $image) && $url)
		{
			$this->navs[] = [
				'order' => $order,
				'name' => $name,
				'parent' => $parent,
				'label' => $label,
				'url' => $url,
				'icon' => $icon,
				'image' => $image,
			];
		}
	}
}