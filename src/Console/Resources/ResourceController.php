<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceController
{

	public function initController()
	{
    	if(!file_exists($this->module_path."/Controllers/"))
    	{
    		\File::makeDirectory($this->module_path."/Controllers", 0755, true);
    	}
    	$fileContent = '<?php

namespace '.$this->controller_namespace.';

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use '.$this->model_namespace.';
use '.$this->request_namespace.'\\'.$this->request_name.';

class '.$this->controller_name.' extends Controller {

	public function index()
	{
		$datas = '.$this->model_name.'::query();
		'.$this->filter_query().'
		$datas = $datas->paginate(10);
		return view("student::student.index",compact("datas"));
	}

	public function create()
	{
		$data = new '.$this->model_name.';
		return view("'.$this->view_name.'.form",compact("data"));
	}

	public function store('.$this->request_name.' $request)
	{
		'.$this->model_name.'::create([
			'.$this->insert_and_update().'
		]);
        return redirect()->route("'.$this->route_name.'.index")->with(["success" => __("Successfully create new data")]);
	}

	public function show('.$this->model_name.' $'.$this->model_var.')
	{
		$data = $'.$this->model_var.';
		return view("'.$this->view_name.'.show",compact("data"));
	}

	public function edit ('.$this->model_name.' $'.$this->model_var.')
	{
		$data = $'.$this->model_var.';
		return view("'.$this->view_name.'.form",compact("data"));
	}

	public function update('.$this->request_name.' $request, '.$this->model_name.' $'.$this->model_var.')
	{
		$'.$this->model_var.'->update([
			'.$this->insert_and_update().'
		]);
        return redirect()->route("'.$this->route_name.'.index")->with(["success" => __("Successfully edited data")]);
	}

	public function destroy('.$this->model_name.' $'.$this->model_var.')
	{
		$'.$this->model_var.'->delete();
        return redirect()->route("'.$this->route_name.'.index")->with(["success" => __("Successfully deleted data")]);
	}
}';
		\File::put($this->controller_path, $fileContent);
	}

	public function filter_query()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			if($column->type == 'text' || $column->type == 'textarea')
			{
				$scripts .= 'if(request()->name)
		{
			$datas->where("'.$column->field.'","like","%".request()->'.$column->field.'."%");
		}';
			}else{
				$scripts .= 'if(request()->dob)
		{
			$datas->where("'.$column->field.'",request()->'.$column->field.');
		}';
			}
		}
		return $scripts;
	}

	public function insert_and_update()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$scripts .= '"'.$column->field.'" => $request->'.$column->field.',
			';
		}
		return $scripts;
	}
}