<div class="container mb-3">
  <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'app') ? 'active' : '' }}" aria-current="page" href="{{ route(config('paagez.route_prefix').".config.app") }}">
          <span>{{__('paagez.application')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'users') ? 'active' : '' }}" aria-current="page" href="{{ route(config('paagez.route_prefix').".config.users.index") }}">
          <span>{{__('paagez.users')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'roles') ? 'active' : '' }}" aria-current="page" href="{{ route(config('paagez.route_prefix').".config.roles.index") }}">
          <span>{{__('paagez.roles')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'modules') ? 'active' : '' }}" aria-current="page" href="{{ route(config('paagez.route_prefix').".config.modules.index") }}">
          <span>{{__('paagez.modules')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'email') ? 'active' : '' }}" aria-current="page" href="{{ route(config('paagez.route_prefix').".config.email") }}">
          <span>{{__('paagez.email')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'rest') ? 'active' : '' }}" aria-current="page" href="{{ route(config('paagez.route_prefix').".config.rest.index") }}">
          <span>{{__('paagez.rest')}}</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ ($active == 'apperance') ? 'active' : '' }} disabled" disabled aria-current="page" href="#">
          <span>{{__('paagez.apperance')}}</span>
        </a>
      </li>
  </ul>
</div>