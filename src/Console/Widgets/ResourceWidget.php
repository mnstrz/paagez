<?php

namespace Monsterz\Paagez\Console\Widgets;

trait ResourceWidget
{
	public function initWidget()
	{
    	if(!file_exists($this->module_path."/Widgets"))
    	{
    		\File::makeDirectory($this->module_path."/Widgets", 0755, true);
    	}

    	$roles = '';
        $roles = \DB::table('roles')->where('guard_name','web')->get()->pluck("name")->toArray();
        $roles = implode('","',$roles);
        $roles = '["'.$roles.'"]';

    	$fileContent = '<?php

namespace '.$this->widget_namespace.';

use Monsterz\Paagez\Classes\Widget\ModuleWidget;

class '.$this->widget_name.' extends ModuleWidget
{
	/**
	 * 
	 * @string
	 * widget position
	 * `main`,`side`,`left`,`right`
	 * 
	 * 
	 * */
	public $position = "'.$this->type.'";

	public $order = 9999;

	public $roles = '.$roles.';

	public function render()
	{
		return view("'.$this->view_name.'");
	}
}';

    	\File::put($this->widget_path, $fileContent);
    }
}