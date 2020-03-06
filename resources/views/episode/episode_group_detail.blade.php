@extends('layouts.app')

@php $title = 'episode group'; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $course_id, '#episodelist']) }}">Course</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
  <div class="row align-items-center justify-content-center mb-2">
    <div class="col-10 col-md-10 col-lg-8">
      <div class="card px-1 py-1 m-0">
        <div class="card-header border-0 pb-0">
          <h4 class="mb-2">EPISODE GROUP</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li><a href="#" onclick="handleChangeList()"><i class="ft-edit"></i> เรียงลำดับ</a></li>
              <li><a href="#" data-toggle="modal" data-target="#addEpisodeGRoup"><i class="ft-plus"></i> เพิ่ม</a></li>
            </ul>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body pt-0">
            <form method="POST" action="{{ route('episode_group_sortgroup') }}">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <ul id="sortable" class="list-group mb-2" onchange="this.form.submit()">
                @foreach ($episode_group as $item)
                  <li id="{{ $item->_id }}" class="list-group-item bg-blue-grey bg-lighten-5 pb-0">
                    <div class="li-custom">
                      <span><i class="ft-menu mr-1 d-none"></i> <strong>{{ $item->title }}</strong></span>
                      <div class="action">
                        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editEP-{{ $item->_id }}">เลือก Episode</button>
                        <button type="button" class="btn btn-outline-danger" onclick="handleDeleteGroup('{{ $item->_id }}')">ลบ</button>
                      </div>
                    </div>
                    <ul class="list-group-inner">
                      @if(!empty($episode_list_selected[$item->_id]))
                        @foreach ($episode_list_selected[$item->_id] as $list)
                          <li class="list-group-item">{{ $list['title'] }}</li>
                        @endforeach
                      @else
                        <li class="list-group-item blue-grey lighten-2">ยังไม่มี Episode</li>
                      @endif
                    </ul>
                  </li>
                @endforeach
              </ul>
              <button id="update-grouplist" type="button" class="btn btn-sortgroup btn-primary d-none">บันทึก</button>
              <button id="cancel-grouplist" type="button" class="btn btn-sortgroup btn-danger d-none">ปิด</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL --}}

  {{-- Modal Add Group --}}
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
        <input type="hidden" name="course_id" value="{{ $course_id }}" />
        <div class="modal-body">
          <fieldset class="form-group floating-label-form-group">
            <label for="user-name">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Title" required>
          </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปิด</button>
          <button type="submit" class="btn btn-outline-primary">บันทึก</button>
        </div>
      </form>
    </div>
    </div>
  </div>

  {{-- Modal select EP --}}
  @foreach ($episode_group as $item)
  <div class="modal fade text-left" id="editEP-{{ $item->_id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel1">{{ $item->title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form method="POST" action="{{ route('episode_update_group_id') }}">
        <div class="modal-body">
          @csrf
          <input type="hidden" name="episode_group_id" value="{{ $item->_id }}">
          <input type="hidden" name="course_id" value="{{ $course_id }}">
          <p>กรุณาเลือก Episode สำหรับ {{ $item->title }}</p>
          <select multiple="multiple" class="episode" name="episode[]">
            @if(!empty($episode_list_selected[$item->_id]))
              @foreach ($episode_list_selected[$item->_id] as $list)
                <option value='{{ $list['id'] }}' selected>{{ $list['title'] }}</option> 
              @endforeach
            @endif
            @foreach ($episode_list_active as $list)
              <option value='{{ $list->_id }}'>{{ $list->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปิด</button>
          <button type="submit" id="update-grouplist" class="btn btn-outline-primary">บันทึก</button>
        </div>
      </form>
    </div>
    </div>
  </div>
  @endforeach

@endsection

@section('style')
  <link href="{{ asset('multiselect/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
  <style>
  .list-active {
    background-color: bisque;
  }
  .ms-container {
    width: 100%;
  }
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
@endsection

@section('script')
<script src="{{ asset('multiselect/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
  $('.episode').multiSelect({ 
    keepOrder: true,
    afterSelect: function(value){
      $('[name="episode[]"] option[value="'+value+'"]').remove();
      $('[name="episode[]"]').append($("<option></option>").attr("value",value).attr('selected', 'selected'));
    }
  });
  
  $('#update-grouplist').on('click', function(){
    var $listItems = $('#sortable li');
    var course_id = '{{ $course_id }}'
    var list_group = $listItems.map(function(){ return this.id}).get()
    handleChangeListDone()
    update_episode_group(course_id,list_group)
  });
  $('#cancel-grouplist').on('click', function(){
    handleChangeListDone()
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
      // swal.fire("Update");
      console.log(data, status)
    });
  }

  function handleChangeList() {
    $('.ft-menu').removeClass('d-none');
    $('.ft-menu').addClass('d-inline');
    $('.btn-sortgroup').removeClass('d-none');
    $('.btn-sortgroup').addClass('d-inline');
    $("#sortable").sortable({
      disabled: false
    });
  }

  function handleChangeListDone() {
    $('.ft-menu').removeClass('d-inline');
    $('.ft-menu').addClass('d-none');
    $('.btn-sortgroup').removeClass('d-inline');
    $('.btn-sortgroup').addClass('d-none');
    $("#sortable").sortable({
      disabled: true
    });
  }

  function handleDeleteGroup(episode_group_id) {
    url = "{{ route('episode_group_delete') }}/"+episode_group_id
    swal.fire({
      title: "คุณต้องการลบใช่หรือไม่ ?",
      icon: "warning",
      showCancelButton: true,
      buttons: {
        cancel: {
          text: "ยกเลิก",
          value: null,
          visible: true,
          className: "",
          closeModal: true,
        },
        confirm: {
          text: "ลบ",
          value: true,
          visible: true,
          className: "",
          closeModal: false
        }
      }
    }).then(isConfirm => {
      if (isConfirm.value) {
        window.location = url
      } 
    });
  }
</script>
@endsection
