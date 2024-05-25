@extends('paagez::layouts.admin')

@push('meta')
<title>{{__('paagez.application')}} - {{config('paagez.app_name')}}</title>
@endpush

@section('contents')
<div class="container mb-3">
	<ul class="d-flex p-2 w-100 align-items-center breadcrumb">
		<li>
			<a href="{{ url(config('paagez.prefix')) }}"><span><i class="fa-solid fa-home"></i></span></a>
		</li>
		<li>
			<a href="{{ route(config('paagez.route_prefix').'.config.app') }}"><span>{{__('paagez.application')}}</span></a>
		</li>
	</ul>
</div>
<div class="container">
	<div class="row">
		@include("paagez::config.tab",['active'=>'app'])
		<div class="col-12">
			<div class="card py-5 px-3 shadow w-100 shadow border-0">
				<x-alert-floating/>
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
						<label class="col-md-3" for="app_logo">{{__('paagez.app_logo')}}</label>
						<div class="col-md-9">
						  @if(config('paagez.app_logo'))
						  <img src="{{config('paagez.app_logo')}}" class="mb-2 img-cover img-rounded" height="70px" width="70px" id="image-logo">
						  @endif
						  <input type="file" class="form-control @error('app_logo') is-invalid @enderror" id="app_logo" name="app_logo" accept="image/*">
						  @error('app_logo')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="prefix">{{__('paagez.prefix')}} <span class="text-danger">*</span></label>
						<div class="col-md-9">
						  <input type="text" class="form-control @error('prefix') is-invalid @enderror" id="prefix" name="prefix" value="{{ (old('prefix')) ? old('prefix') : config('paagez.prefix') }}" required>
						  @error('prefix')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="enable_gcaptcha">{{__('paagez.enable_gcaptcha')}} (V2)</label>
						<div class="col-md-9">
						  <div class="form-row mb-3">
							  <div class="form-check form-switch">
								  <input class="form-check-input" type="checkbox" name="gcaptcha" role="switch" id="gcaptcha" value="1" {{ (old('gcaptcha') || config('paagez.gcaptcha')) ? 'checked' : '' }}>
							  </div>
							  <small class="text-muted">{!!__('paagez.enable_gcaptcha_instruction')!!}</small>
						  </div>
						  @error('pwa')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="enable_pwa">{{__('paagez.enable_pwa')}}</label>
						<div class="col-md-9">
						  <div class="form-row mb-3">
							  <div class="form-check form-switch">
								  <input class="form-check-input" type="checkbox" name="pwa" role="switch" id="pwa" value="1" {{ (old('pwa') || config('paagez.pwa')) ? 'checked' : '' }}>
							  </div>
							  <small class="text-muted">{!!__('paagez.enable_pwa_instruction')!!}</small>
						 </div>
						  @error('pwa')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<label class="col-md-3" for="analytics">{{__('paagez.analytics')}}</label>
						<div class="col-md-9">
						  <textarea type="text" class="form-control form-control-sm @error('analytics') is-invalid @enderror" id="analytics" name="analytics" rows="5">{{ (old('analytics')) ? old('analytics') : config('paagez.analytics') }}</textarea>
						  @error('analytics')
							<small class="invalid-feedback">{{ $message }}</small>
						  @enderror
						</div>
					</div>
					<div class="mb-3 row">
						<div class="col-md-9 offset-md-3">
							<button type="submit" class="btn btn-lg btn-primary btn-sm">{{ __('Update') }}</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="/paagez/plugins/image-preview/image-preview.js?cache={{ config('paagez.cache') }}"></script>
<script type="text/javascript">
	$("#app_logo").imagePreview({
		maxSizeValidation : true,
		autoPreview : false,
		callback : "previewLogo"
	})
	function previewLogo(image)
	{
		 $("#image-logo").attr('src',image)
	}
</script>
@endpush