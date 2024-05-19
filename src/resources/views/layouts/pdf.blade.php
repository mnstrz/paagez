<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" rel="stylesheet" media="all" href="{{ public_path('/theme/css/bootstrap.css') }}?cache={{ config('website.cache') }}">
	<style type="text/css">
		body{
			font-family : sans-serif;
		}
		.page-break {
		    page-break-after: always;
		}
		table {
		  border-collapse: collapse;
		  border-spacing: 0;
		  width: 100%;
		  border: 1px solid #ddd;
		}

		thead tr {
		  background-color: #f5f7ff;
		}

		thead th, td {
			border-bottom: 1px solid #ddd;
		}

		th, td {
		  text-align: left;
		  padding: 16px;
		}

		tbody tr:nth-child(even) {
		  background-color: #f2f2f2;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 d-flex flex-column justify-content-center align-items-center text-center border-bottom mb-4">
				<img src="{{ public_path(config('paagez.app_logo')) }}" alt="Logo" width="30" class="d-inline-block align-text-top">
      			<h3 class="fw-bold text-primary ms-2">{{ config('paagez.app_name') }}</h3>
      			<hr>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				@yield('content')
			</div>
		</div>
	</div>
</body>
</html>