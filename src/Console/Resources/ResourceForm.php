<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceForm
{
	public function initForm()
	{

    	if(!file_exists($this->view_path))
    	{
    		\File::makeDirectory($this->view_path, 0755, true);
    	}
    	$this->form_fields();
    	$fileContent = '@extends("'.$this->layout.'")

@push("meta")

    <title>{{ (!$data->'.$this->primary_key.') ? __("Create '.$this->title.'") : __("Edit '.$this->title.'") }}</title>

    {{-- meta head goes here --}}

@endpush

@section("contents")
    
    <div class="container">
        <div class="row">
        <div class="col-12">
            <div class="card p-3 shadow w-100 position-relative border-0">
                <h4 class="text-primary fw-bold">{{ (!$data->'.$this->primary_key.') ? __("Create '.$this->title.'") : __("Edit '.$this->title.'") }}</h4>
                <x-alert-floating/>
                <form method="POST" action="{{ (!$data->'.$this->primary_key.') ? route("'.$this->route_name.'.store") : route("'.$this->route_name.'.update",[$data->'.$this->primary_key.']) }}" class="py-3">
                    @csrf
                    @if($data->'.$this->primary_key.')
                    <input type="hidden" name="_method" value="PUT">
                    @endif
                    '.$this->form_fields().'
                    <div class="mb-3 row">
                        <div class="col-md-8 col-lg-10 offset-md-4 offset-lg-2">
                            <button type="reset" class="btn btn-secondary me-2">{{__("Reset")}}</button>
                            <button type="submit" class="btn btn-primary">{{__("Submit")}}</button>
                        </div>
                    </div>
                </form>
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
		\File::put($this->view_path."/form.blade.php", $fileContent);
	}

	public function form_fields()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$required = ($column->required) ? 'required' : '';
			if($column->type == 'textarea'){
				$scripts .= '<div class="mb-3 row">
                        <label for="'.$column->field.'" class="col-md-4 col-lg-2 col-form-label">{{__("'.$column->name.'")}}</label>
                        <div class="col-md-8 col-lg-10">
                            <textarea id="'.$column->field.'" name="'.$column->field.'" class="form-control @error("'.$column->field.'") is-invalid @enderror" '.$required.'>{{ old("'.$column->field.'") ? old("'.$column->field.'") : $data->'.$column->field.' }}</textarea>
                            @error("'.$column->field.'")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    ';
			}elseif($column->type == 'date'){
				$scripts .= '<div class="mb-3 row">
                        <label for="'.$column->field.'" class="col-md-4 col-lg-2 col-form-label">{{__("'.$column->name.'")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="date" id="'.$column->field.'" name="'.$column->field.'" class="form-control @error("'.$column->field.'") is-invalid @enderror" value="{{ old("'.$column->field.'") ? old("'.$column->field.'") : $data->'.$column->field.' }}" '.$required.'/>
                            @error("'.$column->field.'")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    ';
			}elseif($column->type == 'number'){
				$scripts .= '<div class="mb-3 row">
                        <label for="'.$column->field.'" class="col-md-4 col-lg-2 col-form-label">{{__("'.$column->name.'")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="number" id="'.$column->field.'" name="'.$column->field.'" class="form-control @error("'.$column->field.'") is-invalid @enderror" value="{{ old("'.$column->field.'") ? old("'.$column->field.'") : $data->'.$column->field.' }}" '.$required.'/>
                            @error("'.$column->field.'")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    ';
			}elseif($column->type == 'time'){
				$scripts .= '<div class="mb-3 row">
                        <label for="'.$column->field.'" class="col-md-4 col-lg-2 col-form-label">{{__("'.$column->name.'")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="time" id="'.$column->field.'" name="'.$column->field.'" class="form-control @error("'.$column->field.'") is-invalid @enderror" value="{{ old("'.$column->field.'") ? old("'.$column->field.'") : $data->'.$column->field.' }}" '.$required.'/>
                            @error("'.$column->field.'")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    ';
			}elseif($column->type == 'text'){
				$scripts .= '<div class="mb-3 row">
                        <label for="'.$column->field.'" class="col-md-4 col-lg-2 col-form-label">{{__("'.$column->name.'")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="text" id="'.$column->field.'" name="'.$column->field.'" class="form-control @error("'.$column->field.'") is-invalid @enderror" value="{{ old("'.$column->field.'") ? old("'.$column->field.'") : $data->'.$column->field.' }}" '.$required.'/>
                            @error("'.$column->field.'")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    ';
			}
		}
		return $scripts;
	}
}