@extends('paagez::layouts.admin')

@push("meta")

    <title>{{ (!$data->id) ? __("Create") : __("Edit") }} {{ __('paagez.users') }}</title>

    {{-- meta head goes here --}}

@endpush

@section("contents")
    <div class="container mb-3">
        <ul class="d-flex p-2 w-100 align-items-center breadcrumb">
            <li>
                <a href="{{ url(config('paagez.prefix')) }}"><span><i class="fa-solid fa-home"></i></span></a>
            </li>
            <li>
                <a href="{{ route(config('paagez.route_prefix').'.config.users.index') }}"><span>{{__('paagez.users')}}</span></a>
            </li>
            @if(!$data->id)
            <li>
                <a href="{{ route(config('paagez.route_prefix').'.config.users.create') }}"><span>{{__('Create')}} {{ __('paagez.users') }}</span></a>
            </li>
            @else
            <li>
                <a href="{{ route(config('paagez.route_prefix').'.config.users.edit',[$data->id]) }}"><span>{{__('Edit')}} {{ __('paagez.users') }}</span></a>
            </li>
            @endif
        </ul>
    </div>
    <div class="container">
        <div class="row">
        <div class="col-12">
            <div class="card p-3 shadow w-100 position-relative border-0">
                <h4 class="text-primary fw-bold">{{ (!$data->id) ? __("Create") : __("Edit") }} {{ __('paagez.users') }}</h4>
                <x-alert-floating/>
                <form method="POST" action="{{ (!$data->id) ? route(config('paagez.route_prefix').".config.users.store") : route(config('paagez.route_prefix').".config.users.update",[$data->id]) }}" class="py-3">
                    @csrf
                    @if($data->id)
                    <input type="hidden" name="_method" value="PUT">
                    @endif
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-lg-2 col-form-label">{{__("Name")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="text" id="name" name="name" class="form-control @error("name") is-invalid @enderror" value="{{ old("name") ? old("name") : $data->name }}" required/>
                            @error("name")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-lg-2 col-form-label">{{__("Email Address")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="email" id="email" name="email" class="form-control @error("email") is-invalid @enderror" value="{{ old("email") ? old("email") : $data->email }}" required/>
                            @error("email")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-lg-2 col-form-label">{{__("Roles")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <div class="d-flex flex-wrap">
                                @foreach($roles as $role)
                                <div class="form-check me-1">
                                  <input class="form-check-input" type="checkbox" value="{{$role->name}}" id="role_{{$role->id}}" name="roles[]" {{ (in_array($role->name,old('roles')??[]) || in_array($role->id,$data->roles?->pluck('id')?->toArray())) ? 'checked' : '' }}>
                                  <label class="form-check-label" for="role_{{$role->id}}">
                                    {{$role->name}} (<i>{{$role->guard_name}}</i>)
                                  </label>
                                </div>
                                @endforeach
                            </div>
                            @error("roles")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-lg-2 col-form-label">{{__("Password")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="password" id="password" name="password" class="form-control @error("password") is-invalid @enderror" value=""/>
                            @if($data->id)
                            <small class="text-muted">{{__('Fill this if only want to change password')}}</small>
                            @endif
                            @error("password")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-lg-2 col-form-label">{{__("Password Confirmation")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error("password_confirmation") is-invalid @enderror" value=""/>
                            @error("password_confirmation")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-8 col-lg-10 offset-md-4 offset-lg-2">
                            <button type="reset" class="btn btn-secondary me-2">{{__("Reset")}}</button>
                            <button type="submit" class="btn btn-primary">{{__("Submit")}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    {{-- your content should be written here --}}

@endsection

@push("styles")

    {{-- css goes here --}}

@endpush

@push("scripts")

    {{-- javascript goes here --}}

    <script type="text/javascript">

        $("#sidebar-app-settings").addClass("active")

    </script>

@endpush