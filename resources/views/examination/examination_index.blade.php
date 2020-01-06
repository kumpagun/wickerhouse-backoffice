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
          <form id="form" class="form" action="#" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $examination_group->_id }}" >
            <div class="form-body">
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- Duration_limit --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->course->has('duration_limit')) danger @endif">
                      <label for="user-name">แบบทดสอบหลังเรียน ทำได้กี่ครั้ง</label>
                      <input type="number" name="duration_limit" class="form-control" value="{{ old('duration_limit', $examination_group->duration_limit) }}" placeholder="duration_limit" required>
                      @if($errors->course->has('duration_limit'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->course->first('duration_limit') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- Duration_sec --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->course->has('duration_sec')) danger @endif">
                      <label for="user-name">ระยะเวลาทำแบบทดสอบแต่ละข้อ</label>
                      <input type="number" name="duration_sec" class="form-control" value="{{ old('duration_sec', $examination_group->duration_sec) }}" placeholder="duration_sec" required>
                      @if($errors->course->has('duration_sec'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->course->first('duration_sec') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    {{-- posttest_passing_score --}}
                    <fieldset class="form-group floating-label-form-group @if($errors->course->has('posttest_passing_score')) danger @endif">
                      <label for="user-name">คะแนนผ่านเกณฑ์</label>
                      <input type="number" name="posttest_passing_score" class="form-control" value="{{ old('posttest_passing_score', $examination_group->posttest_passing_score) }}" placeholder="posttest_passing_score" required>
                      @if($errors->course->has('posttest_passing_score'))
                        <span class="small" role="alert">
                          <p class="mb-0">{{ $errors->course->first('posttest_passing_score') }}</p>
                        </span>
                      @endif
                    </fieldset>
                  </div>
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
      <button class="btn btn-secondary">เพิ่มแบบทดสอบ</button>
    </a>
  </div>
</div>

<div class="row align-items-center justify-content-center">
  @if(!empty($examination))
    @foreach ($examination as $item)
    <div id="{{$item}}" class="col-12 col-md-10 col-xl-8">
      <div class="card">
        <div class="card-header pb-0">
          <h4 class="card-title">{!! $item->question !!}</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li><a href="{{ route('examination_create', ['examination_group_id' => $examination_group->_id, 'id' => $item->_id]) }}"><i class="ft-edit"></i> แก้ไข</a></li>
              <li><a href="#{{$item->_id}}" onclick="handleDelete('{{$item->_id}}')"><i class="ft-x"></i> ลบ</a></a>
            </ul>
          </div>
        </div>
        <div class="card-content">
          <div class="card-body overflow-hidden">
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
      <button class="btn btn-secondary">เพิ่มแบบทดสอบ</button>
    </a>
  </div>
</div>
@endif
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
      swal({
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
        if (isConfirm) {
          window.location = url
        } 
      });
    }
  </script>
@endsection
