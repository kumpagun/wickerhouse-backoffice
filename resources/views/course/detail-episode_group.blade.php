<div id="episodegroup" class="col-12 col-md-10 col-xl-8 mb-4">
  <div class="card px-1 py-1 m-0">
    <div class="card-header border-0 pb-0">
      <h4 class="card-title">EPISODE GROUP</h4>
      <div class="heading-elements">
        <ul class="list-inline mb-0">
          <li>
            @can('editor')
              <a href="{{ route('episode_group_create', ['course_id' => $data->_id]) }}">
                <button class="btn btn-round btn-secondary"><i class="ft-edit"></i> แก้ไข</button>
              </a>
            @else
              <button class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-edit"></i> แก้ไข</button>
            @endcan
          </li>
          <li>
            @can('editor')
              <a href="#" data-toggle="modal" data-target="#addEpisodeGRoup">
                <button class="btn btn-round btn-secondary"><i class="ft-plus"></i> เพิ่ม</button>
              </a>
            @else
              <a><button class="btn btn-round btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-plus"></i> เพิ่ม</button></a>
            @endcan
          </li>
        </ul>
      </div>
      <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
        <span>EPISODE GROUP INFO</span>
      </h6>
    </div>
    <div class="card-content">
      <div class="card-body pt-0">
        <form method="POST" action="{{ route('episode_group_sortgroup') }}">
          @csrf
          <input type="hidden" name="course_id" value="{{ $data->_id }}" />
          @if(!empty($episode_group) && count($episode_group) > 0)
            <ul id="sortable" class="list-group mb-2" onchange="this.form.submit()">
            @foreach ($episode_group as $item)
              <li id="{{ $item->_id }}" class="list-group-item bg-blue-grey bg-lighten-5 pb-0">
                <strong>{{ $item->title }}</strong>
                <ul class="list-group-inner">
                  @if(!empty($episode_list[$item->_id]))
                    @foreach ($episode_list[$item->_id] as $list)
                      <li class="list-group-item">{{ $list['title'] }}</li>
                    @endforeach
                  @else
                    <li class="list-group-item blue-grey lighten-2">ยังไม่มี Episode</li>
                  @endif
                </ul>
              </li>
            @endforeach
            </ul>
          @else
          <table class="table table-bordered">
            <tr>
              <td class="align-baseline text-center">ยังไม่มี Episode Group</td>
            </tr>
          </table>
          @endif
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="addEpisodeGRoup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
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

@push('style')
  <style>
    .list-group-inner {
      margin-top: 10px;
      margin-left: -57px;
      margin-right: -17px;
    }
    .li-custom {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .list-group-inner > .list-group-item {
      border-radius: 0;
      border-left: 0;
      border-right: 0;
      text-indent: 2em;
    }
  </style>
@endpush

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
    var url = "{{ route('episode_group_sortgroup') }}"
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