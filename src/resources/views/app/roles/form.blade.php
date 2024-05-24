@extends('paagez::layouts.admin')

@push("meta")

    <title>{{ (!$data->id) ? __("Create") : __("Edit") }} {{ __('paagez.roles') }}</title>

    {{-- meta head goes here --}}

@endpush

@section("contents")
    <div class="container mb-3">
        <ul class="d-flex p-2 w-100 align-items-center breadcrumb">
            <li>
                <a href="{{ url(config('paagez.prefix')) }}"><span><i class="fa-solid fa-home"></i></span></a>
            </li>
            <li>
                <a href="{{ route(config('paagez.route_prefix').'.app.roles.index') }}"><span>{{__('paagez.roles')}}</span></a>
            </li>
            @if(!$data->id)
            <li>
                <a href="{{ route(config('paagez.route_prefix').'.app.roles.create') }}"><span>{{__('Create')}} {{ __('paagez.roles') }}</span></a>
            </li>
            @else
            <li>
                <a href="{{ route(config('paagez.route_prefix').'.app.roles.edit',[$data->id]) }}"><span>{{__('Edit')}} {{ __('paagez.roles') }}</span></a>
            </li>
            @endif
        </ul>
    </div>
    <div class="container">
        <div class="row">
        <div class="col-12">
            <div class="card p-3 shadow w-100 position-relative border-0">
                <h4 class="text-primary fw-bold">{{ (!$data->id) ? __("Create") : __("Edit") }} {{ __('paagez.roles') }}</h4>
                <x-alert-floating/>
                <form method="POST" action="{{ (!$data->id) ? route(config('paagez.route_prefix').".app.roles.store") : route(config('paagez.route_prefix').".app.roles.update",[$data->id]) }}" class="py-3">
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
                        <label for="guard_name" class="col-md-4 col-lg-2 col-form-label">{{__("Guard")}}</label>
                        <div class="col-md-6 col-lg-6">
                            <input type="text" id="guard_name" name="guard_name" class="form-control @error("guard_name") is-invalid @enderror" value="{{ old("guard_name") ? old("guard_name") : $data->guard_name }}" required/>
                            @error("guard_name")
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