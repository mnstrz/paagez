@extends('paagez::layouts.admin')

@push('meta')
<title>{{__('paagez.modules')}} - {{config('paagez.app_name')}}</title>
@endpush

@section('contents')
	<div class="container mb-3">
		<ul class="d-flex p-2 w-100 align-items-center breadcrumb">
			<li>
				<a href="{{ url(config('paagez.prefix')) }}"><span><i class="fa-solid fa-home"></i></span></a>
			</li>
			<li>
				<a href="{{ route(config('paagez.route_prefix').'.app.modules.index') }}"><span>{{__('paagez.modules')}}</span></a>
			</li>
		</ul>
	</div>
	<div class="container">
		<div class="row">
			@include("paagez::app.tab",['active'=>'modules'])
	        <div class="col-12">
	            <x-alert-floating/>
	            @if(count($not_installed??[]) > 0)
	            <div class="card p-3 shadow w-100 shadow border-0 mb-3">
	                <h6 class="text-muted">{{__('paagez.not_installed')}}</h6>
	                <table class="table table-hover">
	                	<thead>
	                		<tr>
	                			<th>{{__('paagez.module')}}</th>
	                			<th>{{__('paagez.module_name')}}</th>
	                			<th>{{__('paagez.version')}}</th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		@foreach($not_installed as $index => $item)
	                		<tr>
	                			<td>
	                				<small class="text-warning">\module\{{$item->name}}</small> <br>
	                			</td>
	                			<td>{{$item->title}}</td>
	                			<td>{{$item->version}}</td>
	                		</tr>
	                		@endforeach
	                	</tbody>
	                </table>
	                <div class="d-flex justify-content-end">
	                	<a href="{{ route(config('paagez.route_prefix').".update") }}" class="btn btn-outline-primary"><i class="fa-solid fa-arrow-right"></i> {{__('paagez.install')}}</a>
	                </div>
	            </div>
	            @endif
	            @if(count($installed??[]) > 0)
	            <div class="card p-3 shadow w-100 shadow border-0 mb-3">
	                <table class="table table-hover">
	                	<thead>
	                		<tr>
	                			<th>{{__('paagez.module')}}</th>
	                			<th>{{__('paagez.module_name')}}</th>
	                			<th>{{__('paagez.version')}}</th>
	                			<th>{{__('paagez.enable')}}</th>
	                			<th></th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		@foreach($installed as $index => $item)
	                		<tr>
	                			<td>
	                				<small class="text-warning">\module\{{$item->name}}</small> <br>
	                			</td>
	                			<td>{{$item->title}}</td>
	                			<td>{{$item->version}}</td>
	                			<td>
	                				<div class="form-check form-switch">
									  <input class="form-check-input change-module-status" type="checkbox" role="switch" id="enable_{{$item->name}}" data-url="{{route(config('paagez.route_prefix').".app.modules.change-status",[$item->name])}}" {{($item->active) ? 'checked' : ''}}>
									</div>
	                			</td>
	                			<td>
	                				<a href="{{ route(config('paagez.route_prefix').".app.modules.show",[$item->name]) }}" class="btn btn-outline-primary btn-sm" data-bs-toggle='tooltip' data-bs-title="{{__('Show')}}"><i class="fa-solid fa-list"></i></a>
	                				<a href="{{ url(config('paagez.prefix')."/".$item->route_prefix."/config") }}" class="btn btn-outline-primary btn-sm" data-bs-toggle='tooltip' data-bs-title="{{__('Config')}}"><i class="fa-solid fa-gear"></i></a>
	                			</td>
	                		</tr>
	                		@endforeach
	                	</tbody>
	                </table>
	            </div>
	            @endif
	        </div>
	    </div>
	</div>
@endsection

@push('scripts')


    <script type="text/javascript">

        $("#sidebar-app-settings").addClass("active")

        $(document).on('change','.change-module-status',function()
        {
        	var url = $(this).data('url')
        	$.ajax({
        		url : url
        	})
        })

    </script>
@endpush