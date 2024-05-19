<nav class="navbar navbar-expand-lg bg-white shadow">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
      <img src="{{ config('website.logo') }}" alt="Logo" width="30" class="d-inline-block align-text-top">
      <h5 class="text-dark ms-2 mb-0">{{ config('app.name') }}</h5>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item me-3">
            <a class="nav-link" aria-current="page" href="{{ route('index') }}">{{ __('Home') }}</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-secondary" aria-current="page" href="{{ route('index') }}"><i class="{{ \App\Helpers\General::peranIcon(\Auth::user()->peran) }}"></i> {{ \Auth::user()->nama_lengkap }}</a>
            <div class="dropdown shadow">
              <label class="badge bg-secondary fw-bold">{{ \Str::title(\Auth::user()->perans?->nama_peran) }}</label>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <a href="{{ route('profile') }}"><i class="fa-solid fa-user"></i> {{ __('Profile') }}</a>
                </li>
                <li class="list-group-item">
                  <a href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> {{ __('Logout') }}</a>
                </li>
              </ul>
            </div>
          </li>
      </ul>
    </div>
  </div>
</nav>