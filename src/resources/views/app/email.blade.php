@extends('paagez::layouts.admin')

@push('meta')
<title>{{__('paagez.email')}} - {{config('paagez.app_name')}}</title>
@endpush

@section('contents')
<div class="container mb-3">
	<ul class="d-flex p-2 w-100 align-items-center breadcrumb">
		<li>
			<a href="{{ url(config('paagez.prefix')) }}"><span><i class="fa-solid fa-home"></i></span></a>
		</li>
		<li>
			<a href="{{ route(config('paagez.route_prefix').'.app.email') }}"><span>{{__('paagez.email')}}</span></a>
		</li>
	</ul>
</div>
<div class="container">
	<div class="row">
		@include("paagez::app.tab",['active'=>'email'])
		<div class="col-12">
			<div class="card py-5 px-3 shadow w-100 shadow border-0">
				<x-alert-floating/>
				<form action="" method="POST" class="px-3" enctype="multipart/form-data">
					@csrf
					<div class="mb-3 row">
						<label class="col-md-3 col-form-label" for="mailer">{{__('paagez.mailer')}} <span class="text-danger">*</span></label>
						<div class="col-md-4">
						  <select class="form-select" name="mailer" id="mailer">
						  		@foreach($mailers as $mailer => $label)
						  		<option value="{{$mailer}}" {{ (old('mailer') == $mailer || config('paagez.mail_mailer') == $mailer) ? 'selected' : '' }}>{{$label}}</option>
						  		@endforeach
						  </select>
						  @error('mailer')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row" id="conf_host">
                        <label for="host" class="col-md-3 col-form-label">{{__("paagez.host")}}</label>
                        <div class="col-md-6">
                            <input type="text" id="host" name="host" class="form-control @error("host") is-invalid @enderror" value="{{ old("host") ? old("host") : config('paagez.mail_host') }}" />
						    @error('host')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_port">
                        <label for="port" class="col-md-3 col-form-label">{{__("paagez.port")}}</label>
                        <div class="col-md-3">
                            <input type="text" id="port" name="port" class="form-control @error("port") is-invalid @enderror" value="{{ old("port") ? old("port") : config('paagez.mail_port') }}"/>
						    @error('port')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_encryption">
                        <label for="encryption" class="col-md-3 col-form-label">{{__("paagez.encryption")}}</label>
                        <div class="col-md-3">
                            <input type="text" id="encryption" name="encryption" class="form-control @error("encryption") is-invalid @enderror" value="{{ old("encryption") ? old("encryption") : config('paagez.mail_encryption') }}" />
						    @error('encryption')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_username">
                        <label for="username" class="col-md-3 col-form-label">{{__("paagez.username")}}</label>
                        <div class="col-md-4">
                            <input type="text" id="username" name="username" class="form-control @error("username") is-invalid @enderror" value="{{ old("username") ? old("username") : config('paagez.mail_username') }}"/>
						    @error('username')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_password">
                        <label for="password" class="col-md-3 col-form-label">{{__("paagez.password")}}</label>
                        <div class="col-md-4">
                            <input type="text" id="password" name="password" class="form-control @error("password") is-invalid @enderror" value="{{ old("password") ? old("password") : config('paagez.mail_password') }}"/>
						    @error('password')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_local_domain">
                        <label for="local_domain" class="col-md-3 col-form-label">{{__("paagez.local_domain")}}</label>
                        <div class="col-md-6">
                            <input type="text" id="local_domain" name="local_domain" class="form-control @error("local_domain") is-invalid @enderror" value="{{ old("local_domain") ? old("local_domain") : config('paagez.mail_local_domain') }}"/>
						    @error('local_domain')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_path">
                        <label for="path" class="col-md-3 col-form-label">{{__("paagez.path")}}</label>
                        <div class="col-md-6">
                            <input type="text" id="path" name="path" class="form-control @error("path") is-invalid @enderror" value="{{ old("path") ? old("path") : config('paagez.mail_path') }}"/>
						    @error('path')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row" id="conf_channel">
                        <label for="channel" class="col-md-3 col-form-label">{{__("paagez.channel")}}</label>
                        <div class="col-md-6">
                            <input type="text" id="channel" name="channel" class="form-control @error("channel") is-invalid @enderror" value="{{ old("channel") ? old("channel") : config('paagez.mail_channel') }}"/>
						    @error('channel')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row">
                        <label for="from_address" class="col-md-3 col-form-label">{{__("paagez.from_address")}}</label>
                        <div class="col-md-3">
                            <input type="email" id="from_address" name="from_address" class="form-control @error("from_address") is-invalid @enderror" value="{{ old("from_address") ? old("from_address") : config('paagez.mail_from_address') }}"/>
						    @error('from_address')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row">
                        <label for="from_name" class="col-md-3 col-form-label">{{__("paagez.from_name")}}</label>
                        <div class="col-md-3">
                            <input type="text" id="from_name" name="from_name" class="form-control @error("from_name") is-invalid @enderror" value="{{ old("from_name") ? old("from_name") : config('paagez.mail_from_name') }}"/>
						    @error('from_name')
								<small class="invalid-feedback">{{ $message }}</small>
						  	@enderror
                        </div>
					</div>
					<div class="mb-3 row">
						<div class="col-md-9 offset-md-3 d-flex">
							<button type="reset" class="btn btn-lg btn-secondary btn-sm">{{ __('Reset') }}</button>
							<button type="submit" class="btn btn-lg btn-primary btn-sm ms-1">{{ __('Update') }}</button>
							<a href="{{ route(config('paagez.route_prefix').".app.email.reset") }}" class="btn btn-lg btn-outline-secondary btn-sm confirm ms-auto" data-swal-title="Warning" data-swal-text="{{__("Do you want to reset this settings?")}}" data-swal-icon="info" data-swal-confirm-text="{{__("Yes, reset")}}" data-swal-cancel-text="{{__("Cancel")}}">{{ __('Reset to default') }}</a>
						</div>
					</div>
				</form>
				<form action="{{route(config('paagez.route_prefix').".app.email.test")}}" method="POST" class="p-3 mt-3 border-top" enctype="multipart/form-data">
					<div class="mb-3 row">
						<div class="col-12">
							<h5>{{__('paagez.test_email')}}</h5>
						</div>
					</div>
					@csrf
					<div class="mb-3 row">
						<label class="col-md-3 col-form-label" for="mailer">{{__('paagez.to')}}</label>
						<div class="col-md-4">
							<input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required />
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3 col-form-label" for="mailer">{{__('paagez.subject')}}</label>
						<div class="col-md-4">
							<input type="text" id="subject" name="subject" class="form-control" required value="{{ old('subject') }}" />
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3 col-form-label" for="mailer">{{__('paagez.message')}}</label>
						<div class="col-md-9">
							<textarea class="form-control" rows="5" required name="message">{{ old('message') }}</textarea>
						</div>
					</div>
					<div class="mb-3 row">
						<div class="col-md-9 offset-md-3 d-flex">
							<button type="submit" class="btn btn-lg btn-primary btn-sm ms-1">{{__('paagez.test_email')}}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')

    <script type="text/javascript">

        $("#sidebar-app-settings").addClass("active")

        configureEmail()

        $(document).on('change','#mailer',function()
        {
        	configureEmail();
        })

        function configureEmail()
        {
    		$("#conf_host").hide()
    		$("#conf_port").hide()
    		$("#conf_encryption").hide()
    		$("#conf_username").hide()
    		$("#conf_password").hide()
    		$("#conf_local_domain").hide()
    		$("#conf_path").hide()
    		$("#conf_channel").hide()

    		var mailer = $("#mailer").val()

        	if(mailer == 'smtp')
        	{
        		$("#conf_host").show()
        		$("#conf_port").show()
        		$("#conf_encryption").show()
        		$("#conf_username").show()
        		$("#conf_password").show()
        		$("#conf_local_domain").show()
        	}else if(mailer == 'sendmail'){
        		$("#conf_path").show()
        	}else if(mailer == 'log')
        	{
        		$("#conf_channel").show()
        	}
        }

    </script>
@endpush