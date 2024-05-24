<nav class="navbar">
	<a href="#" class="brand ms-5"><img src="{{ config('paagez.app_logo') }}"></a>
	@if(count($navs) > 0)
	<ul class="nav left-nav">
		@foreach($navs as $nav)
			@if(isset($nav['child']) && count($nav['child']) > 0)
			<li class="{{ ($nav['active']) ? 'active' : '' }}" id="sidebar-{{$nav['name']}}">
				<a href="#" class="nav-dropdown">
					@if(isset($nav['icon']) && $nav['icon'])
						<i class="{{$nav['icon']}}"></i> 
					@elseif(isset($nav['image']) && $nav['image'])
						<img src="{{$nav['image']}}">
					@else
					<i class="fa-solid fa-grid-2"></i> 
					@endif
					@if($nav['label'])
					<span class="text-primary">{!!$nav['label']!!}</span>
					@endif
				</a>
				<ul>
				@foreach($nav['child'] as $child)
					<li class="{{ ($child['active']) ? 'active' : '' }}" id="sidebar-{{$child['name']}}">
						<a href="{{$child['url']}}">{!!$child['label']!!}</a>
					</li>
				@endforeach
				</ul>
			</li>
			@else
			<li class="{{ ($nav['active']) ? 'active' : '' }}" id="sidebar-{{$nav['name']}}">
				<a href="{{ $nav['url'] }}">
					@if(isset($nav['icon']) && $nav['icon'])
						<i class="{{$nav['icon']}}"></i> 
					@endif
					@if(isset($nav['image']) && $nav['image'])
						<img src="{{$nav['image']}}">
					@endif
					@if($nav['label'])
					<span class="text-primary">{!!$nav['label']!!}</span>
					@endif
				</a>
			</li>
			@endif
		@endforeach
	</ul>
	@endif
	<ul class="nav ms-auto">
		<li class="d-block d-lg-none">
			<a href="#" id="toggle-navbar-menu"><i class="fa-solid fa-grid text-primary"></i></a>
		</li>
		@if($update)
		<li>
			<a href="{{ route(config('paagez.route_prefix').".update") }}" class="btn btn-pill btn-outline-primary link">{{__('Update')}} <span class="fa-fade bg-success"></span></a>
		</li>
		@endif
		<li>
			<a href="#" class="link">
				<i class="fa-solid fa-bell"></i>
				@if(count($notifications) > 0)
				<span>{{count($notifications)}}</span>
				@endif
			</a>
			<ul>
				@if(count($notifications) > 0)
					@foreach($notifications as $notification)
					<li>
						<a href="{{ url($notification->url) }}">
							<small>{{$notification->subject}}</small> <br>
							<small class="text-muted fs-xs">{{$notification->date}}</small>
						</a>
					</li>
					@endforeach
				@else
				<li>
					<div class="text-center w-100 fs-xs text-muted">{{__('paagez.no_notif')}}</div>
				</li>
				@endif
				<li class="w-100 text-center">
					<a href="{{ route(config('paagez.route_prefix').".notifications.index") }}" class="fs-sm text-primary">{{ __('paagez.all_notif') }}</a>
				</li>
			</ul>
		</li>
		<li>
			<a href="#" class="link"><i class="fa-solid fa-user"></i></a>
			<ul>
				<li>
					<a href="#"><i class="fa-solid fa-user"></i> {{ \Auth::user()->name }}</a>
				</li>
				<li>
					<a href="{{ route('paagez.logout') }}"><i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}</a>
				</li>
			</ul>
		</li>
	</ul>
</nav>