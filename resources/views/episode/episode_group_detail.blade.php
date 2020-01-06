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
<div class="row align-items-center justify-content-center">
  <div class="col-10 col-md-8">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        <h4 class="mb-2">EPISODE GROUP</h4>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form method="POST" action="{{ route('episode_group_updatelist') }}">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <ul id="sortable" class="list-group mb-2" onchange="this.form.submit()">
              @foreach ($episode_group as $item)
                <li id="{{ $item->_id }}" class="list-group-item @if($id==$item->_id) list-active @endif">
                  <a href="{{ route('episode_group_create', ['course_id' => $data->course_id, 'id' => $item->_id]) }}"><strong>{{ $item->title }}</strong></a>
                </li>
              @endforeach
            </ul>
            <button id="update-grouplist" type="button" class="btn btn-primary">อัพเดท list</button>
          </form>
        </div>
    </div>
  </div>
</div>
@endsection

@section('style')
  <style>
  .list-active {
    background-color: bisque;
  }
  </style>
@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
  $("#sortable").sortable();
  $('#update-grouplist').on('click', function(){
    var $listItems = $('#sortable li');
    var course_id = '{{ $course_id }}'
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
@endsection
