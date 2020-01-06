@extends('layouts.app')

@php $title = $data->title; @endphp

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
              <li><a href="#" onclick="handleChangeList()"><i class="ft-edit"></i> แก้ไข</a></li>
              <li><a href="#"><i class="ft-x"></i> ลบ</a></li>
            </ul>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body pt-0">
            <form method="POST" action="{{ route('episode_group_sortgroup') }}">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <ul id="sortable" class="list-group mb-2" onchange="this.form.submit()">
                @foreach ($episode_group as $item)
                  <li id="{{ $item->_id }}" class="list-group-item pb-0">
                    <div class="li-custom">
                      <span><i class="ft-menu mr-1 d-none"></i> <strong>{{ $item->title }}</strong></span>
                      <div class="action">
                        <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editEP-{{ $item->_id }}">แก้ไข</button>
                        <button type="button" class="btn btn-outline-danger">ลบ</button>
                      </div>
                    </div>
                    <ul class="list-group-inner">
                      <li class="list-group-item">ยังไม่มี Episode</li>
                    </ul>
                  </li>
                @endforeach
              </ul>
              <button id="update-grouplist" type="button" class="btn btn-sortgroup btn-primary d-none">Save Change</button>
              <button id="cancel-grouplist" type="button" class="btn btn-sortgroup btn-danger d-none">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MODAL --}}
  @foreach ($episode_group as $item)
  <div class="modal fade text-left" id="editEP-{{ $item->_id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel1">EPISODE GROUP</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form method="POST" action="{{ route('episode_update_group_id') }}">
        <div class="modal-body">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <input type="hidden" value="{{ $item }}">
          <select multiple="multiple" id="episode" name="episode[]">
            @foreach ($episode_list_active as $item)
              <option value='{{ $item->_id }}'>{{ $item->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
          <button type="button" id="update-grouplist" class="btn btn-outline-primary">Save changes</button>
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
  }
  </style>
@endsection

@section('script')
<script src="{{ asset('multiselect/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
  $('#episode').multiSelect()
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
      swal("Update");
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
</script>
@endsection
