<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceDatabase
{

	public $table = [];

	public $primary_key = '';

	public $table_name = '';
	
	public function getTableInformation()
	{
		$model = new $this->model_namespace;
		$tableName = $model->getTable();

		if(!$tableName)
		{
			$this->error('Table not found');
			return 1;
		}

		$this->primary_key = $model->getKeyName();

		$this->table_name = $tableName;

		$columns = \DB::select("SHOW COLUMNS FROM {$tableName}");

		foreach ($columns as $column) {
		    $type = $column->Type;
		    $length = null;
		    
		    if (preg_match('/^(\w+)(\((\d+)\))?/', $type, $matches)) {
		        $type = $matches[1];
		        if (isset($matches[3])) {
		            $length = $matches[3];
		        }
		    }

		    if($column->Field != 'created_at' && $column->Field != 'updated_at' && $column->Field != 'deleted_at' && $column->Field != $this->primary_key)
		    {
			    $this->table[] = (object) [
			    	'field' => $column->Field,
			    	'name' => \Str::title(preg_replace('/[-_]/',' ',$column->Field)),
			    	'length' => ($length !== null) ? $length : null,
			    	'type' => $this->getColumnType($type),
			    	'required' => ($column->Null == 'YES') ? false : true,
			    ];
		    }
		}
	}

	protected function getColumnType($type)
	{
		switch ($type) {
			case 'varchar':
			case 'char':
			    return 'text';
				break;
			case 'int':
			case 'double':
			case 'float':
			case 'float':
			case 'tinyint':
			    return 'number';
				break;
			case 'date':
			case 'datetime':
			    return 'date';
				break;
			case 'time':
			    return 'time';
				break;
			case 'text':
			    return 'textarea';
				break;
			default:
			    return 'text';
				break;
		}
	}
}