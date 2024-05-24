<div class="row">
	<div class="col-12">
		@if($launchers)
		<div class="row mb-3 align-items-stretch p-1">
			@foreach($launchers as $launcher)
				@hasanyrole($launcher['roles'])
				<div class="col-4 col-md-3 col-lg-2 p-2">
					<a href="{{$launcher['url']}}" class="launcher">
						@if($launcher['image'])
						<img src="{{$launcher['image']}}">
						@elseif($launcher['icon'])
						<i class="{{$launcher['icon']}} fa-3x"></i>
						@else
						<i class="fa-solid fa-grid-2 fa-3x"></i>
						@endif
						<span>{{$launcher['label']}}</span>
					</a>
				</div>
				@endhasanyrole
			@endforeach
		</div>
		@endif
	</div>
</div>