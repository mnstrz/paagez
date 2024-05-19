<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceRequest
{
	public function initRequest()
	{
    	if(!file_exists($this->module_path."/Requests/"))
    	{
    		\File::makeDirectory($this->module_path."/Requests", 0755, true);
    	}
		$fileContent = '<?php

namespace '.$this->request_namespace.';

use Illuminate\Foundation\Http\FormRequest;

class '.$this->request_name.' extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $data = $this->route()?->parameter("'.$this->model_var.'");
        $id = ($data) ? $data->'.$this->primary_key.' : null;
        return ['.$this->request_column().'
        ];
    }

    public function attributes()
    {
        return ['.$this->attributes_column().'
        ];
    }
}';
		\File::put($this->request_path, $fileContent);
	}

	public function request_column()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {

			$required = ($column->required) ? '"required",' : '"nullable",';
			$date = ($column->type == 'date') ? '"date",' : '';
			$email = ($column->field == 'email') ? '"email",' : '';
			$numeric = ($column->type == 'number') ? '"numeric",' : '';
			$max = ($column->length && $column->type != 'number') ? '"max:'.$column->length.'",' : '';
			$digits = ($column->type == 'number') ? '"digits_between:1,'.$column->length.'",' : '';
			$unique = ($key == 0) ? '(!$id) ? "unique:'.$this->table_name.','.$column->field.'" : "unique:'.$this->table_name.','.$column->field.',".$id,' : '';
			$scripts .= '
			"'.$column->field.'" => ['.$required.$date.$email.$numeric.$max.$digits.$unique.'],';
		}
		return $scripts;
	}

	public function attributes_column()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$scripts .= '
            "'.$column->field.'"  => __("'.$column->name.'"),';
		}
		return $scripts;
	}
}