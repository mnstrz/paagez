@if($breadcrumbs)
<div class="container mb-3">
	<ul class="d-flex p-2 w-100 align-items-center breadcrumb">
		@foreach($breadcrumbs as $url => $label)
			<li>
				<a href="{{$url}}">
					<span>{!! $label !!}</span>
				</a>
			</li>
		@endforeach
	</ul>
</div>
@endif