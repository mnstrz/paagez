<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceShow
{
	public function initShow()
	{

    	if(!file_exists($this->view_path))
    	{
    		\File::makeDirectory($this->view_path, 0755, true);
    	}
    	$fileContent = '@extends("'.$this->layout.'")

@push("meta")

    <title>{{ __("Show '.$this->title.'") }}</title>

    {{-- meta head goes here --}}

@endpush

@section("contents")
    
    <div class="container">
        <div class="row">
        <div class="col-12">
            <div class="card p-3 shadow w-100 position-relative border-0">
                <h4 class="text-primary fw-bold mb-3">{{ __("Show '.$this->title.'") }}</h4>
                '.$this->show_fields().'
            </div>
        </div>
    </div>
    </div>
    {{-- your content should be written here --}}

@endsection

@push("styles")

    {{-- css goes here --}}

@endpush

@push("scripts")

    {{-- javascript goes here --}}

    <script type="text/javascript">

        {{-- add script to enable menu for $("#sidebar-student").addClass("active") --}}

    </script>

@endpush';

		\File::put($this->view_path."/show.blade.php", $fileContent);
	}

	public function show_fields()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$scripts .= '<div class="row mb-3">
                    <div class="col-md-4 col-lg-2">{{__("'.$column->name.'")}}</div>
                    <div class="col-md-6 col-lg-8"><p class="lead">{{$data->'.$column->field.'}}</p></div>
                </div>
                ';
		}
		return $scripts;
	}
}