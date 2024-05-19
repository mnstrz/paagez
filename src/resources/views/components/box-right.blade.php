@php
	$id = (isset($id)) ? $id : \Str::random(10);
	$conf = (isset($conf)) ? $conf : '';
@endphp
<div class="floating-box floating-box-right {{$conf}}" id="{{$id}}">
	<button type="button" class="btn btn-outline-danger btn-sm btn-floating ms-auto"><i class="fa-solid fa-circle-xmark"></i></button>
	<div class="w-100 content">
		{!! $slot !!}
	</div>
</div>
<button type="button" class="btn btn-primary btn-floating-box"><i class="fa-solid fa-chevron-right"></i></button>