@if (session()->has('success'))
    <div class="alert  alert-success alert-pill alert-dismissible fade show animate__animated animate__bounceInRight" role="alert">
      {!! session('success') !!}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('error'))
    <div class="alert  alert-danger alert-pill alert-dismissible fade show animate__animated animate__bounceInRight text-white" role="alert">
      {!! session('error') !!}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('info'))
    <div class="alert  alert-info alert-pill alert-dismissible fade show animate__animated animate__bounceInRight text-white" role="alert">
      {!! session('info') !!}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif(session()->has('warning'))
    <div class="alert  alert-warning alert-pill alert-dismissible fade show animate__animated animate__bounceInRight" role="alert">
      {!! session('warning') !!}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@else
    @if ($errors->any())
    <div class="alert  alert-warning alert-pill alert-dismissible fade show animate__animated animate__bounceInRight" role="alert">
      @foreach($errors->all() as $error)
        <small>{!! $error !!}</small><br>
      @endforeach
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
@endif