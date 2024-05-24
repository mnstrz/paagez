@if($tabs && $active)
<div class="container mb-3">
  <ul class="nav nav-tabs">
    @foreach($tabs as $url => $label)
      <li class="nav-item">
        <a class="nav-link {{ ($url == $active) ? 'active' : '' }}" aria-current="page" href="#">
          <span>{!! $label !!}</span>
        </a>
      </li>
    @endforeach
  </ul>
</div>
@endif