<div id="episodegroup" class="col-12 col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">EPISODE GROUP</h4>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>EPISODE INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        <form method="POST" action="{{ route('episode_group_updatelist') }}">
          @csrf
          <input type="hidden" name="course_id" value="{{ $data->_id }}" />
          @if(!empty($episode_group))
            <ul id="sortable" class="list-group mb-2" onchange="this.form.submit()">
            @foreach ($episode_group as $item)
              <li id="{{ $item->_id }}" class="list-group-item">
                <a href="{{ route('episode_group_create', ['course_id' => $data->_id, 'id' => $item->_id]) }}"><strong>{{ $item->title }}</strong></a>
              </li>
            @endforeach
            </ul>
            <button id="update-grouplist" type="button" class="btn btn-primary">อัพเดท list</button>
          @endif
          <button type="button" data-repeater-create class="btn btn-secondary"  data-toggle="modal" data-target="#default">
            <i class="ft-plus"></i> เพิ่ม Episode group
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">EPISODE GROUP</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('episode_group_store') }}" method="POST">
      @csrf
      <input type="hidden" name="course_id" value="{{ $data->_id }}" />
      <div class="modal-body">
        <fieldset class="form-group floating-label-form-group @if($errors->course->has('title')) danger @endif">
          <label for="user-name">Title</label>
          <input type="text" name="title" class="form-control" placeholder="Title" required>
        </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-outline-primary">Save changes</button>
      </div>
    </form>
  </div>
  </div>
</div>

@push('scripts')
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
  <script>
  $("#sortable").sortable();
  $('#update-grouplist').on('click', function(){
    var $listItems = $('#sortable li');
    var course_id = '{{ $data->_id }}'
    var list_group = $listItems.map(function(){ return this.id}).get()

    update_episode_group(course_id,list_group)
  });

  function update_episode_group(course_id, list_group)
  {
    var url = "{{ route('episode_group_updatelist') }}"
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $.post(url,
    {
      _token: CSRF_TOKEN,
      course_id: course_id,
      episode_group: list_group
    },
    function(data, status){
      swal("Update");
    });
  }
  </script>
@endpush