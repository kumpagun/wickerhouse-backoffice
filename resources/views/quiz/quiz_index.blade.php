@extends('layouts.app')

@php $title = 'เพิ่ม, แก้ไขแบบทดสอบหลัง EP : '.CourseClass::get_episode_name($quiz_group->episode_id); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $quiz_group->course_id, '#quiz']) }}">Course</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-12 col-md-10 col-xl-8">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }} Detail</h4>
      </div>
      <div class="card-content">
        <div class="card-body overflow-hidden">
          <form id="form" class="form" action="{{ route('quiz_detail_store') }}" method="POST">
            @csrf
            <input type="hidden" name="quiz_group_id" value="{{ $quiz_group->_id }}" >
            <div class="form-body">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- passing_point --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->episode->has('passing_point')) danger @endif">
                      <label for="user-name">คะแนนผ่านเกณฑ์</label>
                      <input type="number" name="passing_point" class="form-control" value="{{ old('passing_point', $episode->passing_point) }}" placeholder="passing_point">
                      @if($errors->episode->has('passing_point'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->episode->first('passing_point') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 mt-2 text-right">
                  <button id="btnSubmit" class="btn btn-primary btn-block" type="submit">บันทึก</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row align-items-center justify-content-center mb-2">
  <div class="col-12 col-md-10 col-xl-8 text-center">
    <a href="{{ route('quiz_create',['quiz_group_id' => $quiz_group->_id]) }}">
      <button class="btn btn-secondary mx-1">เพิ่มแบบทดสอบ</button>
    </a>
    <a href="#" data-toggle="modal" data-target="#importexcel">
      <button class="btn btn-secondary mx-1">Import EXCEL</button>
    </a>
  </div>
</div>

<div class="row align-items-center justify-content-center">
  @if(!empty($quiz))
    @foreach ($quiz as $item)
    <div id="{{$item}}" class="col-12 col-md-10 col-xl-8">
      <div class="card">
        <div class="card-header pb-0">
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li><a href="{{ route('quiz_create', ['quiz_group_id' => $quiz_group->_id, 'id' => $item->_id]) }}"><i class="feather icon-edit"></i> แก้ไข</a></li>
              <li><a href="#{{$item->_id}}" onclick="handleDelete('{{$item->_id}}')"><i class="feather icon-x"></i> ลบ</a></a>
            </ul>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body overflow-hidden">
            <h4 class="card-title">{!! $item->question !!}</h4>
            <div class="row">
              @foreach ($item->choice as $index => $choice)
              <div class="col-12 col-md-6">
                <div class="choice @if($index==$item->answer_key) bg-amber bg-lighten-4 @else  bg-blue-grey bg-lighten-4 @endif">
                  {!! $choice['title'] !!}
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>   
    @endforeach
  @endif
</div>

@if(count($quiz) > 5)
<div class="row align-items-center justify-content-center mb-2">
  <div class="col-12 col-md-10 col-xl-8 text-center">
    <a href="{{ route('quiz_create',['quiz_group_id' => $quiz_group->_id]) }}">
      <button class="btn btn-secondary mx-1">เพิ่มแบบทดสอบ</button>
    </a>
    <a href="#" data-toggle="modal" data-target="#importexcel">
      <button class="btn btn-secondary mx-1">Import EXCEL</button>
    </a>
  </div>
</div>
@endif


<div class="modal fade text-left" id="importexcel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">IMPORT EXCEL</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('quiz_import_excel') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="quiz_group_id" value="{{ $quiz_group->_id }}" />
      <div class="modal-body">
        <fieldset class="form-group floating-label-form-group @if($errors->course->has('title')) danger @endif">
          <label for="user-name">File excel</label>
          <input name="excel" class="form-control" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
          <a target="_blank" href="/File/exam-test.xlsx"><p class="mt-1">ตัวอย่างไฟล์</p></a>
        </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปืด</button>
        <button type="submit" class="btn btn-outline-primary">บันทึก</button>
      </div>
    </form>
  </div>
  </div>
</div>
@endsection

@section('style')
  <style>
    img {
      max-width: 100%;
    }
    .choice {
      max-width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border-radius: .5em;
    }
    .choice > p {
      margin-bottom: 0;
    }
  </style>
@endsection

@section('script')
  <script>
    function handleDelete(id) {
      url = "{{ route('quiz_delete') }}/"+id
      swal.fire({
        title: "คุณต้องการลบคำถามใช่หรือไม่ ?",
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
