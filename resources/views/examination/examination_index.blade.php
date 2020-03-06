@extends('layouts.app')

@php $title = strtoupper(implode(',',$examination_group->type)); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $examination_group->course_id, '#examination']) }}">Course</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  @if(in_array('posttest',$examination_group->type))
  <div class="col-12 col-md-10 col-xl-8">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }} Detail</h4>
      </div>
      <div class="card-content">
        <div class="card-body overflow-hidden">
          <form id="form" class="form" action="{{ route('examination_posttest_update') }}" method="POST">
            @csrf
            <input type="hidden" name="examination_group_id" value="{{ $examination_group->_id }}" >
            <input type="hidden" name="course_id" value="{{ $course->_id }}" >
            <div class="form-body">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- posttest_limit_total --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->course->has('posttest_limit_total')) danger @endif">
                      <label for="user-name">แบบทดสอบหลังเรียน ทำได้กี่ครั้ง</label>
                      <input type="number" name="posttest_limit_total" class="form-control" value="{{ old('posttest_limit_total', $course->posttest_limit_total) }}" placeholder="posttest_limit_total">
                      @if($errors->course->has('posttest_limit_total'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->course->first('posttest_limit_total') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- posttest_duration_sec --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->course->has('posttest_duration_sec')) danger @endif">
                      <label for="user-name">ระยะเวลาทำแบบทดสอบแต่ละข้อ (วินาที)</label>
                      <input type="number" name="posttest_duration_sec" class="form-control" value="{{ old('posttest_duration_sec', $course->posttest_duration_sec) }}" placeholder="posttest_duration_sec" >
                      @if($errors->course->has('posttest_duration_sec'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->course->first('posttest_duration_sec') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- posttest_passing_point --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->course->has('posttest_passing_point')) danger @endif">
                      <label for="user-name">คะแนนผ่านเกณฑ์</label>
                      <input type="number" name="posttest_passing_point" class="form-control" value="{{ old('posttest_passing_point', $course->posttest_passing_point) }}" placeholder="posttest_passing_point">
                      @if($errors->course->has('posttest_passing_point'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->course->first('posttest_passing_point') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="col-12">
                  {{-- display answer --}}
                  <fieldset>
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="posttest_display_answer" id="posttest_display_answer" @if($course->posttest_display_answer) checked @endif>
                      <label class="custom-control-label" for="posttest_display_answer">แสดงเฉลยเมื่อทำแบบทดสอบหลังเรียนเสร็จ</label>
                    </div>
                  </fieldset>
                </div>
                <div class="col-12 mt-2 text-right">
                  <button id="btnSubmit" class="btn btn-primary btn-block" type="submit">Save</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

<div class="row align-items-center justify-content-center mb-2">
  <div class="col-12 col-md-10 col-xl-8 text-center">
    <a href="{{ route('examination_create',['examination_group_id' => $examination_group->_id]) }}">
      <button class="btn btn-secondary mx-1">เพิ่มแบบทดสอบ</button>
    </a>
    <a href="#" data-toggle="modal" data-target="#importexcel">
      <button class="btn btn-secondary mx-1">Import EXCEL</button>
    </a>
  </div>
</div>

<div class="row align-items-center justify-content-center">
  @if(!empty($examination))
    @foreach ($examination as $item)
    <div id="{{$item}}" class="col-12 col-md-10 col-xl-8">
      <div class="card">
        <div class="card-header pb-0">
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li><a href="{{ route('examination_create', ['examination_group_id' => $examination_group->_id, 'id' => $item->_id]) }}"><i class="feather icon-edit"></i> แก้ไข</a></li>
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

@if(count($examination) > 5)
<div class="row align-items-center justify-content-center mb-2">
  <div class="col-12 col-md-10 col-xl-8 text-center">
    <a href="{{ route('examination_create',['examination_group_id' => $examination_group->_id]) }}">
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
    <form class="form-horizontal" action="{{ route('examination_import_excel') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="examination_group_id" value="{{ $examination_group->_id }}" />
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
      url = "{{ route('examination_delete') }}/"+id
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
