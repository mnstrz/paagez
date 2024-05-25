@extends('paagez::layouts.admin')

@push('meta')
<title>{{__('paagez.roles')}} - {{config('paagez.app_name')}}</title>
@endpush

@section('contents')
	<div class="container mb-3">
		<ul class="d-flex p-2 w-100 align-items-center breadcrumb">
			<li>
				<a href="{{ url(config('paagez.prefix')) }}"><span><i class="fa-solid fa-home"></i></span></a>
			</li>
			<li>
				<a href="{{ route(config('paagez.route_prefix').'.config.roles.index') }}"><span>{{__('paagez.roles')}}</span></a>
			</li>
		</ul>
	</div>
    <div class="container">
        <div class="row">
            @include("paagez::config.tab",['active'=>'roles'])
            <div class="col-12">
                <div class="card p-3 shadow w-100 shadow border-0">
                    <x-alert-floating/>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <button type="button" class="btn btn-outline-primary btn-sm {{ (count(request()->except('page')) > 0) ? 'btn-secondary border-secondary text-white' : '' }}" data-filter-target="#filter"><i class="fa-solid fa-filter"></i> {{__("Filter")}}</button>
                        </div>
                        <div class="d-flex">
                            <a href="{{ route(config('paagez.route_prefix').'.config.roles.create') }}" class="btn btn-primary btn-sm ms-1"><i class="fa-solid fa-plus"></i> {{__("Create New")}}</a>
                        </div>
                    </div>
                    <div class="position-relative w-100">
                        <div class="position-absolute d-none bg-white rounded shadow w-50 z-3" id="filter">
                            <form class="p-3 border-bottom">
                                <div class="mb-1">
                                    <label for="name">{{ __("Name") }}</label>
                                    <input type="text" class="form-control form-control-sm" name="name" id="name" value="{{ request()->name }}">
                                </div>
                                <div class="mb-1">
                                    <label for="name">{{ __("Guard") }}</label>
                                    <input type="text" class="form-control form-control-sm" name="guard" id="guard" value="{{ request()->guard }}">
                                </div>
                                <div class="mt-3 d-flex">
                                    <button type="submit" class="btn btn-primary btn-sm me-1">{{ __("Filter") }}</button>
                                    <a href="{{ route(config('paagez.route_prefix').'.config.roles.index') }}" class="btn btn-secondary btn-sm">{{ __("Reset") }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Guard")}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($datas) > 0)
                                @foreach($datas as $index => $data)
                                <tr>
                                    <td>{{ ($datas->perPage()*($datas->currentPage() - 1))+($index+1) }}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->guard_name}}</td>
                                    <td>
                                        @if($data->id != 1 && ($data->name != 'user' && $data->guard_name != 'api'))
                                        <a href="{{route(config('paagez.route_prefix').'.config.roles.edit',[$data->id])}}" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __("Edit") }}"><i class="fa-solid fa-edit"></i></a>
                                        <button type="button" class="btn-sm btn-danger btn confirm" data-form="#delete-{{$data->id}}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ __("Delete") }}" data-swal-title="Warning" data-swal-text="{{__("Do you want to delete this data ?")}}" data-swal-icon="info" data-swal-confirm-text="{{__("Delete")}}" data-swal-cancel-text="{{__("Cancel")}}"><i class="fa-solid fa-trash"></i></button>
                                        <form method="POST" action="{{route(config('paagez.route_prefix').'.config.roles.destroy',[$data->id])}}" id="delete-{{$data->id}}">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>
                                        @else
                                        <i>{{__('paagez.roles_default')}}</i>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="text-center p-5">{{__("paagez.no_data_found")}}</td>
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
    </div>

@endsection

@push('scripts')


    <script type="text/javascript">

        $("#sidebar-app-settings").addClass("active")

    </script>
@endpush