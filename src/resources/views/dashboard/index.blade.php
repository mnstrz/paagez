@extends('paagez::layouts.admin')

@push('meta')
<title>{{config('paagez.app_name')}}</title>
@endpush
@section('contents')
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h3 class="fw-bold text-primary mb-3">Hi, {{\Auth::user()->name}}</h3>
			<x-launcher/>
			<x-dashboard/>
		</div>
		<div class="col-md-4">
			<x-alert-floating/>
			<x-widget-dashboard/>
		</div>
	</div>
</div>
@endsection