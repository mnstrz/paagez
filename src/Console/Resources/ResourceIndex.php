<?php

namespace Monsterz\Paagez\Console\Resources;

trait ResourceIndex
{
	public function initIndex()
	{
    	if(!file_exists($this->view_path))
    	{
    		\File::makeDirectory($this->view_path, 0755, true);
    	}
    	$fileContent = '@extends('.$this->layout.')

@push("meta")

    <title>{{__("'.$this->title.'")}}</title>

    {{-- meta head goes here --}}

@endpush

@section("contents")
    
    <div class="row">
        <div class="col-12">
            <div class="card p-3 shadow w-100 shadow border-0">
                <h4 class="text-primary fw-bold">{{__("'.$this->title.'")}}</h4>
                <x-alert-floating/>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm {{ (count(request()->except('."'".'page'."'".')) > 0) ? '."'".'btn-secondary border-secondary text-white'."'".' : '."''".' }}" data-filter-target="#filter"><i class="fa-solid fa-filter"></i> {{__("Filter")}}</button>
                    </div>
                    <div class="d-flex">
                        <a href="{{ route('.$this->route_name.'.".create") }}" class="btn btn-primary btn-sm ms-1"><i class="fa-solid fa-plus"></i> {{__("Create New")}}</a>
                    </div>
                </div>
                <div class="position-relative w-100">
                    <div class="position-absolute d-none bg-white rounded shadow w-50 z-3" id="filter">
                        <form class="p-3 border-bottom">
                            '.$this->filter().'
                            <div class="mt-3 d-flex">
                                <button type="submit" class="btn btn-primary btn-sm me-1">{{ __("Filter") }}</button>
                                <a href="{{ route('.$this->route_name.'.".index") }}" class="btn btn-secondary btn-sm">{{ __("Reset") }}</a>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            '.$this->table_head().'
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($datas) > 0)
                            @foreach($datas as $index => $data)
                            <tr>
                                <td>{{ ($datas->perPage()*($datas->currentPage() - 1))+($index+1) }}</td>
                                '.$this->table_column().'
                                <td>
                                    <a href="{{route('.$this->route_name.'.".show",[$data->'.$this->primary_key.'])}}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __("Show") }}"><i class="fa-solid fa-list"></i></a>
                                    <a href="{{route('.$this->route_name.'.".edit",[$data->'.$this->primary_key.'])}}" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __("Edit") }}"><i class="fa-solid fa-edit"></i></a>
                                    <button type="button" class="btn-sm btn-danger btn confirm" data-form="#delete-{{$data->'.$this->primary_key.'}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __("Delete") }}" data-swal-title="Warning" data-swal-text="{{__("Do you want to delete this data ?")}}" data-swal-icon="info" data-swal-confirm-text="{{__("Delete")}}" data-swal-cancel-text="{{__("Cancel")}}"><i class="fa-solid fa-trash"></i></button>
                                    <form method="POST" action="{{route('.$this->route_name.'.".destroy",[$data->'.$this->primary_key.'])}}" id="delete-{{$data->'.$this->primary_key.'}}">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="'.(count($this->table)+2).'" class="text-center p-5">{{__("paagez.no_data_found")}}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="d-flex justify-content-center z-1">
                    {{ $datas->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push("styles")

    {{-- css goes here --}}

@endpush

@push("scripts")

    {{-- javascript goes here --}}

@endpush';
		\File::put($this->view_path."/index.blade.php", $fileContent);
	}

	public function filter()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$type = 'text';
			if($column->type == 'date')
			{
				$type = 'date';
			}elseif($column->type == 'number')
			{
				$type = 'number';
			}
			$scripts .= '<div class="mb-1">
                            <label for="name">{{ __("'.$column->name.'") }}</label>
                            <input type="'.$type.'" class="form-control form-control-sm" name="'.$column->field.'" id="'.$column->field.'" value="{{ request()->'.$column->field.' }}">
                        </div>
                        ';
		}
		return $scripts;
	}

	public function table_head()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$scripts .= '<th>{{__("'.$column->name.'")}}</th>
                            ';
		}
		return $scripts;
	}

	public function table_column()
	{
		$scripts = '';
		foreach ($this->table as $key => $column) {
			$scripts .= '<td>{{$data->'.$column->field.'}}</td>
                            ';
		}
		return $scripts;
	}
}