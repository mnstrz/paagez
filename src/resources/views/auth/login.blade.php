@extends('paagez::layouts.app')
@push('meta')
<title>{{__('Login')}} - {{ config('paagez.app_name') }}</title>
@endpush
@section('contents')
<section id="login">
	<div class="container-fluid h-100">
		<div class="row flex-row-reverse align-items-center h-100">
			<div class="col-md-6 right-side p-3 d-flex align-items-center flex-column justify-content-center">
				<div class="d-flex flex-column align-items-center mb-3">
      				<img src="{{ config('paagez.app_logo') }}" alt="Logo" width="50" class="d-inline-block align-text-top mb-1">
      				<h3 class="fw-bold text-primary ms-2">{{ config('paagez.app_name') }}</h3>
				</div>
				<form action="" method="POST" class="px-3 mt-5">
					<h3 class="fw-bold" id="login-title"><i></i> {{__('Login')}}</h3>
					<x-alert-floating/>
					@csrf
					<div class="mb-3">
						<div class="form-floating">
						  <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Alamat Email" name="email" value="{{ old('email') }}">
						  <label for="email">{{ __('Email Address') }}</label>
						  @error('email')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3">
						<div class="form-floating">
						  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="{{ __('Password') }}" name="password">
						  <label for="password">{{ __('Password') }}</label>
						  @error('password')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3">
						<div class="form-floating mb-3">
							<button class="btn btn-lg btn-primary w-100 text-white fw-bold">{{ __('Login') }}</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-6 left-side">
			</div>
		</div>
	</div>
</section>
@endsection
@push('styles')
	@if(config('paagez.gcaptcha'))
	{!! ReCaptcha::htmlScriptTagJsApi() !!}
	@endif
@endpush
@push('scripts')
@endpush