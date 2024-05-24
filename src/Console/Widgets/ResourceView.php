<?php

namespace Monsterz\Paagez\Console\Widgets;

trait ResourceView
{
	public function initView()
	{
		if(!file_exists($this->module_path."/resources/views/widget-".$this->type))
    	{
    		\File::makeDirectory($this->module_path."/resources/views/widget-".$this->type, 0755, true);
    	}

    	if($this->type == 'main' || $this->type == 'side')
    	{
    		$fileContent = '<div class="row">
    <div class="col-12">
        <div class="card p-3 shadow border-0">
        	<h5 class="text-primary">'.$this->title.'</h5>
            {{-- your content should be written here --}}
        </div>
    </div>
</div>';
    	}else{
    		$fileContent = '<div class="row">
    <div class="col-12">
    	<h5 class="text-primary">'.$this->title.'</h5>
        {{-- your content should be written here --}}
    </div>
</div>';
    	}

    	\File::put($this->view_path, $fileContent);
	}
}