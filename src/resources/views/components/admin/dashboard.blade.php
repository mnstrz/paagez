@if(count($dashboards) > 0)
	@foreach($dashboards as $dashboard)
	<div class="row mb-3">
		<div class="col-12">
			{!! $dashboard['views'] !!}
		</div>
	</div>
	@endforeach
@endif