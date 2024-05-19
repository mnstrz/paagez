@if($contents)
<div class="floating-box floating-box-right" id="{{$id}}">
	<button type="button" class="btn btn-outline-danger btn-sm btn-floating ms-auto"><i class="fa-solid fa-circle-xmark"></i></button>
	<div class="w-100 content">
		{!! $contents !!}
	</div>
</div>
<button type="button" class="btn btn-primary btn-floating-box"><i class="fa-solid fa-chevron-right"></i></button>
@endif