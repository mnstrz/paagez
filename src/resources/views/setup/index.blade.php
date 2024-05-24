@extends(config('paagez.theme').'::layouts.app')
@push('meta')
<title>{{__('Install')}}</title>
@endpush
@section('contents')
<section id="login">
	<div class="container-fluid h-100">
		<div class="row flex-row-reverse align-items-center h-100">
			<div class="col-md-6 mx-auto card bg-white border shadow p-5">
				<div class="d-flex flex-column align-items-center mb-3">
      				<h5 class="fw-bold text-primary text-center">{{__('paagez.first_use')}}</h5>
				</div>
				<div class="mb-3 d-flex flex-wrap px-3 justify-content-center">

				</div>
				<form action="" method="POST" class="px-3" enctype="multipart/form-data">
					@csrf
					<div class="mb-3 row">
						<label class="col-md-3" for="app_name">{{__('paagez.app_name')}} <span class="text-danger">*</span></label>
						<div class="col-md-9">
						  <input type="text" class="form-control @error('app_name') is-invalid @enderror" id="app_name" name="app_name" value="{{ (old('app_name')) ? old('app_name') : config('paagez.app_name') }}" required>
						  @error('app_name')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="app_name">{{__('paagez.app_logo')}}</label>
						<div class="col-md-9">
						  @if(config('paagez.app_logo'))
						  <img src="{{config('paagez.app_logo')}}" height="50px">
						  @endif
						  <input type="file" class="form-control @error('app_logo') is-invalid @enderror" id="app_logo" name="app_logo" accept="image/*">
						  @error('app_logo')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="app_name">{{__('Username')}} <span class="text-danger">*</span></label>
						<div class="col-md-9">
						  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ (old('name')) ? old('name') : '' }}" required>
						  @error('name')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="app_name">{{__('Email')}} <span class="text-danger">*</span></label>
						<div class="col-md-9">
						  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ (old('email')) ? old('email') : '' }}" required>
						  @error('email')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="app_name">{{__('Password')}} <span class="text-danger">*</span></label>
						<div class="col-md-9">
						  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
						  @error('password')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="app_name">{{__('Repeat password')}} <span class="text-danger">*</span></label>
						<div class="col-md-9">
						  <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required>
						</div>
					</div>
					<div class="mb-3">
						<span class="text-danger">*)</span> <small>{{__('Required')}}</small>
					</div>
					<div class="mb-3">
						<div class="form-floating mb-3">
							<button class="btn btn-lg btn-primary w-100 text-white fw-bold">{{ __('Install') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/setup/setup.js') }}"></script>
@endpush