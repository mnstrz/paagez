@php
  $id = (isset($id)) ? $id : '';
  $conf = (isset($conf)) ? $conf : '';
@endphp
<div class="modal" tabindex="-1" id="{{$id}}">
  <div class="modal-dialog {{$conf}}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{$slot}}
      </div>
    </div>
  </div>
</div>