@extends(config('paagez.theme').'::layouts.admin')
@push('meta')
<title>{{__('paagez.module_updates')}} - {{ config('paagez.app_name') }}</title>
@endpush
@section('contents')
<div class="row p-5">
	<div class="col-12 p-5 shadow rounded bg-white">
		<div class="d-flex flex-column align-items-center mb-3">
				<h5 class="fw-bold text-primary text-center">{{__('paagez.module_updates')}}</h5>
		</div>
		<div class="p-3 mb-3"style="max-height: 600px;overflow: auto;">
			<input type="hidden" id="url_update_package" value="{{route(config('paagez.route_prefix').".update.package")}}">
			<input type="hidden" id="url_update_database" value="{{route(config('paagez.route_prefix').".update.database")}}">
			<input type="hidden" id="url_update_module" value="{{route(config('paagez.route_prefix').".update.module")}}">
			@if(count($updates_app) > 0)
			<table class="table table-hover">
				<tbody>
				@foreach($updates_app as $index => $module)
					<tr data-module="{{$module['name']}}" data-index="{{$index}}">
						<td>
							<div class="d-flex align-items-center">
								<i class="fa-solid fa-hourglass-start fa-flip text-warning fa-lg me-2" id="loading_{{$module['name']}}" style="display:none"></i>
								<div class="d-flex flex-column">
									<strong>{{$module['title']}}</strong>
									<small class="text-muted">{{$module['name']}}</small>
								</div>
							</div>
						</td>
						<td>
							{{$module['version']}} <br/>
							@if(!$module['installed'])
							<label class="badge bg-success">{{__('paagez.new')}}</label>
							@else
							<label class="badge bg-info">{{__('paagez.update')}}</label>
							<small class="text-muted">{{__('paagez.installed')}}: {{$module['latest_version']}}</small>
							@endif
						</td>
						<td class="text-end">
							@if(!$module['installed'])
								@if($module['error_messages'])
								<label class="badge bg-danger">{{__('paagez.cant_install')}}</label>
								@else
								<label class="badge bg-success">{{__('paagez.ready_install')}}</label>
								<button type="button" class="btn btn-sm btn-outline-primary ms-1" data-module="{{$module['name']}}" data-index="{{$index}}" data-label="{{__('paagez.install')}}">{{__('paagez.install')}}</button>
								@endif
							@else
								@if($module['error_messages'])
								<label class="badge bg-danger">{{__('paagez.cant_update')}}</label>
								@else
								<label class="badge bg-success">{{__('paagez.ready_update')}}</label>
								<button type="button" class="btn btn-sm btn-outline-primary ms-1" data-module="{{$module['name']}}" data-index="{{$index}}" data-label="{{__('paagez.update')}}">{{__('paagez.update')}}</button>
								@endif
							@endif
						</td>
					</tr>
					@foreach($module['error_messages'] as $message)
					<tr>
						<td colspan="3"><small><i class="fa-solid fa-times text-danger"></i> {{$message}}</small></td>
					</tr>
					@endforeach
					@foreach($module['warning_messages'] as $message)
					<tr>
						<td colspan="3"><small><i class="fa-solid fa-circle text-warning"></i> {{$message}}</small></td>
					</tr>
					@endforeach
				@endforeach
				</tbody>
			</table>
			@else
			<h5 class="text-muted my-5 text-center">{{__('paagez.no_updates')}}</h5>
			@endif
		</div>
		<div class="w-100 p-3 text-success" id="success-message" style="display: none">{{__('paagez.all_updated')}}</div>
		<div class="d-flex mb-3 justify-content-end">
			@if(count($updates_app) > 0)
			<button class="btn btn-primary {{ (!$install) ? 'disabled' : '' }}" id="install-update" {{ (!$install) ? 'disabled' : '' }}>{{__('paagez.run_update')}}</button>
			@endif
		</div>
	</div>
</div>
@endsection
@push('styles')
<style type="text/css">
	tr td p{
		margin: 0px;
	}
</style>
@endpush
@push('scripts')
<script type="text/javascript" src="/paagez/js/update.js?cache={{ config('paagez.cache') }}"></script>
@endpush