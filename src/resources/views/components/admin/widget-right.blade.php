@if(count($widgets) > 0)
<div class="floating-box floating-box-right" id="{{$id}}">
	<button type="button" class="btn btn-outline-danger btn-sm btn-floating ms-auto"><i class="fa-solid fa-circle-xmark"></i></button>
	<div class="content">
		@foreach($widgets as $widget)
			<div class="row mb-3">
				<div class="col-12">
					{!! $widget['views'] !!}
				</div>
			</div>
		@endforeach
	</div>
</div>
<button type="button" class="btn btn-primary btn-floating-box"><i class="fa-solid fa-chevron-left"></i></button>
@endif