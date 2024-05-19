@php
	$active = (isset($active)) ? $active : '';
@endphp
<nav class="sidebar">
	<button type="button" class="btn btn-sidebar-close"><i class="fa-regular fa-bars"></i></button>
	<div class="d-flex flex-column align-items-center mb-4">
		<img src="{{ config('paagez.app_logo') }}" class="brand" alt="Logo" class="d-inline-block align-text-top mb-1">
		<h5 class="fw-bold text-primary ms-2">{{ config('paagez.app_name') }}</h5>
	</div>
	<div class="menu">
		<ul>{{-- 
			<li class="{{ ($active == 'dashboard') ? 'active' : '' }}">
				<a href="#"><i class="fa-solid fa-grid-2"></i> {{__('Dashboard')}}</a>
			</li>
			<li class="{{ ($active == 'configuration') ? 'active' : '' }}">
				<a href="#" class="nav-dropdown"><i class="fa-solid fa-wrench"></i> {{__('Configuration')}}</a>
				<ul>
					<li><a href="#">{{__('Website')}}</a></li>
					<li><a href="#">{{__('Administrator')}}</a></li>
					<li><a href="#">{{__('Facilites')}}</a></li>
				</ul>
			</li> --}}
			@foreach($menus as $menu)
				@if(isset($menu['child']) && count($menu['child']) > 0)
				<li class="{{ ($menu['active']) ? 'active' : '' }}" id="sidebar-{{$menu['name']}}">
					<a href="#" class="nav-dropdown">
						@if(isset($menu['icon']) && $menu['icon'])
							<i class="{{$menu['icon']}}"></i> 
						@elseif(isset($menu['image']) && $menu['image'])
							<img src="{{$menu['image']}}">
						@else
						<i class="fa-solid fa-grid-2"></i> 
						@endif
						<span>{!!$menu['label']!!}</span>
					</a>
					<ul>
					@foreach($menu['child'] as $child)
						<li class="{{ ($child['active']) ? 'active' : '' }}" id="sidebar-{{$child['name']}}">
							<a href="{{$child['url']}}">{!!$child['label']!!}</a>
						</li>
					@endforeach
					</ul>
				</li>
				@else
				<li class="{{ ($menu['active']) ? 'active' : '' }}" id="sidebar-{{$menu['name']}}">
					<a href="{{ $menu['url'] }}">
						@if(isset($menu['icon']) && $menu['icon'])
							<i class="{{$menu['icon']}}"></i> 
						@endif
						@if(isset($menu['image']) && $menu['image'])
							<img src="{{$menu['image']}}">
						@endif
						<span>{!!$menu['label']!!}</span>
					</a>
				</li>
				@endif
			@endforeach
		</ul>
	</div>
</nav>
<button type="button" class="btn btn-sidebar"><i class="fa-regular fa-bars"></i></button>