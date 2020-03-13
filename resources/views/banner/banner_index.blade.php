@extends('layouts.app')

@php $title = strtoupper('แบนเนอร์'); @endphp

@section('content-header-left')
    <h3 class="content-header-title mb-2">{{ $title }}</h3>
    <div class="row breadcrumbs-top">
      <div class="breadcrumb-wrapper col-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">{{ $title }}</li>
        </ol>
      </div>
    </div>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
    @can('editor')
      <a class="btn btn-secondary" href="{{ route('banner_create') }}">เพิ่ม{{ $title }}</a>
    @else 
      <button class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>เพิ่ม{{ $title }}</button>
    @endcan
  </div>
@endsection

@section('content')
  <div class="row align-items-center justify-content-center mb-2">
    <div class="col-12 col-md-10 col-xl-8">
      @if (session('status'))
      <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <strong>Success</strong> บันทึกเรียบร้อยแล้ว
      </div>
      @endif
    </div>
    <div class="col-12 col-md-10 col-xl-8">
      <div class="card px-1 py-1 m-0">
        <div class="card-header border-0 pb-0">
          <h4 class="mb-2">{{$title}}</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li><a href="#" onclick="handleChangeList()"><i class="ft-edit"></i> เรียงลำดับ</a></li>
            </ul>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body pt-0">
            <form method="POST" action="{{ route('banner_sort') }}">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <ul id="sortable" class="list-group mb-2" onchange="this.form.submit()">
                @foreach ($datas as $item)
                  <li id="{{ $item->_id }}" class="list-group-item bg-blue-grey bg-lighten-5 py-0 mb-1">
                    <ul class="list-group-inner mt-0">
                      <li class="list-group-item">
                        <div><img src="{{ config('app.url').'storage/'.$item->image_path }}" alt=""></div>
                        <div class="action">
                          <button type="button" class="btn btn-outline-danger" onclick="handleDeleteGroup('{{ $item->_id }}')">ลบ</button>
                        </div>
                      </li>
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
@endsection

@section('style')
  <link href="{{ asset('multiselect/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
  <style>
    img {
      width: 100%;
    }
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
    display: flex;
    justify-content: space-between;
  }
  .action {
    padding: 10px 5px 10px 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
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
    var list_group = $listItems.map(function(){ return this.id}).get()
    handleChangeListDone()
    update_sortable(list_group)
  });
  $('#cancel-grouplist').on('click', function(){
    handleChangeListDone()
  });

  function update_sortable(list_group)
  {
    var url = "{{ route('banner_sort') }}"
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
    $.post(url,
    {
      _token: CSRF_TOKEN,
      list: list_group
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

  function handleDeleteGroup(banner_id) {
    url = "{{ route('banner_delete') }}/"+banner_id
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
