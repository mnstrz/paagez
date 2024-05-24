@extends(config('paagez.theme').'::layouts.admin')
@push('meta')
<title>{{__('paagez.notifications')}} - {{ config('paagez.app_name') }}</title>
@endpush
@section('contents')
<div class="container">
	<div class="row">
		<div class="col-12">
			<x-alert-floating/>
			<div class="card shadow p-4 rounded border-0">
				<div class="d-flex w-100 align-items-center justify-content-between mb-3">
					<h4 class="text-primary m-0">{{ __('paagez.notifications') }}</h4>
					<a href="{{ route(config('paagez.route_prefix').'.notifications.read.all') }}" class="btn btn-sm btn-outline-primary">{{__('paagez.mark_all_as_read')}}</a>
				</div>
				<table class="table table-hover">
					<tbody>
						@if(count($notifications) > 0)
							@foreach($notifications as $notification)
							<tr>
								<td class="{{ (!$notification->read_at) ? 'bg-blue-100' : '' }}" width="200px">
									<a href="{{$notification->url}}" class="text-decoration-underline">{{$notification->subject}}</a>
								</td>
								<td class="{{ (!$notification->read_at) ? 'bg-blue-100' : '' }}">
									<small class="text-muted">{{$notification->message}}</small>
								</td>
								<td width="200px" class="text-muted text-end {{ (!$notification->read_at) ? 'bg-blue-100' : '' }}">
									<small>{{$notification->date}}</small>
								</td>
							</tr>
							@endforeach
						@else
							<tr>
                            	<td colspan="3" class="text-center p-5">{{__("paagez.no_data_found")}}</td>
							</tr>
						@endif
					</tbody>
				</table>
				<div class="d-flex justify-content-center">
					{!! $notifications->links() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('styles')

@endpush
@push('scripts')

@endpush