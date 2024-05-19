<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" type="image/x-icon" href="{{ config('paagez.app_logo') }}">
	@stack('meta')
	@stack('css')
	<link rel="stylesheet" type="text/css" rel="stylesheet" media="all" href="/theme/css/bootstrap.css?cache={{ config('paagez.cache') }}">
    <link rel="stylesheet" type="text/css" rel="stylesheet" media="all" href="/theme/plugins/fontawesome/all.css"/>
    <link rel="stylesheet" type="text/css" rel="stylesheet" media="all" href="/theme/plugins/animate/animate.css"/>
    <link rel="stylesheet" type="text/css" rel="stylesheet" media="all" href="/theme/plugins/spinkit/spinkit.min.css"/>
	<meta property="og:locale" content="{{ \App::getLocale() }}" />
	@stack('styles')
</head>
<body id="app">
	<div class="main {{config('paagez.boxed')?'container':''}}" id="admin">
		<x-admin-navbar/>
		<div class="main-content">
			<x-admin-breadcrumb/>
			<x-admin-tab/>
			@yield('contents')
			@if(config('paagez.footer_mode') == 'full')
			<x-admin-footer/>
			@endif
		</div>
	</div>
	<script src="/theme/js/popper.min.js"></script>
	<script src="/theme/js/moment.min.js"></script>
	<script src="/theme/js/jquery.min.js"></script>
	<script src="/theme/js/bootstrap.bundle.min.js" defer></script>
	<script src="/theme/plugins/sweetalert2/sweetalert2.js"></script>
	<script src="/theme/plugins/alert/alert.js"></script>
	@stack('js')
	<script type="text/javascript">
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		let LOCALE = $("html").attr('lang')
	</script>
	<script type="text/javascript" src="paagez/js/main.js?cache={{ config('paagez.cache') }}"></script>
	@stack('scripts')
</body>
</html>