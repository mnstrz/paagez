@if(count($widgets) > 0)
	@foreach($widgets as $widget)
	<div class="row mb-3">
		<div class="col-12">
			{!! $widget['views'] !!}
		</div>
	</div>
	@endforeach
@endif