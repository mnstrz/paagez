@extends('paagez::layouts.admin')

@push('meta')
<title>{{__('paagez.modules')}} - {{config('paagez.app_name')}}</title>
@endpush

@section('contents')
	<div class="container">
		<div class="row">
	        <div class="col-12">
	            <div class="card p-3 shadow w-100 shadow border-0 mb-3">
	                <div class="d-flex justify-content-end">
	                	<a href="{{ url(config('paagez.prefix')."/".$moduleclass->route_prefix."/config") }}" class="btn btn-outline-primary btn-sm" data-bs-toggle='tooltip' data-bs-title="{{__('Config')}}"><i class="fa-solid fa-gear"></i></a>
	                </div>
	            	<table class="table table-borderless">
	            		<tbody>
	            			@if($moduleclass->logo && file_exists(public_path($moduleclass->logo)))
	            			<tr>
	            				<th colspan="2">
	            					<img src="{{$moduleclass->logo}}" width="72px" height="72px" class="img-cover img-circle">
	            				</th>
	            			</tr>
	            			@endif
	            			<tr>
	            				<th width="200px">{{__('paagez.module')}}</th>
	            				<td>{{ $moduleclass->name }}</td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.module_name')}}</th>
	            				<td>{{ $moduleclass->title }}</td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.version')}}</th>
	            				<td>{{ $moduleclass->version }}</td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.publisher')}}</th>
	            				<td>{{ $moduleclass->publisher }}</td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.email_publisher')}}</th>
	            				<td>{{ $moduleclass->email_publisher }}</td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.website_publisher')}}</th>
	            				<td>{{ $moduleclass->website_publisher }}</td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.route_prefix')}}</th>
	            				<td><code>/{{ $moduleclass->route_prefix }}</code></td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.admin_route_prefix')}}</th>
	            				<td><code>/{{ config('paagez.prefix') }}/{{ $moduleclass->route_prefix }}</code></td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.route_name')}}</th>
	            				<td><code>{{ config('paagez.route_prefix') }}.{{ $moduleclass->route_name }}.</code></td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.namespace')}}</th>
	            				<td><code>{{ $moduleclass->namespace }}</code></td>
	            			</tr>
	            			<tr>
	            				<th>{{__('paagez.last_updated')}}</th>
	            				<td>{{ \Carbon\Carbon::parse($module->updated_at)->translatedFormat('d F Y H:i') }}</td>
	            			</tr>
	            		</tbody>
	            	</table>
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