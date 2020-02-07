@extends('layouts.app')

@php $title = strtoupper('แบบประเมินหลักสูตรออนไลน์'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $review_group->course_id, '#review']) }}">Course</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center mb-4">
  <div class="col-12 col-md-8 col-lg-6 mb-2">
    <h4 class="card-title text-center">{{ $title }}</h4>
    <p class="text-center">หัวข้อ: {{ $review_group->title }}</p>
  </div>
  <div class="col-12 text-center">
    @can('editor')
      <a data-toggle="modal" data-target="#addAnswerChoice">
        <button class="btn btn-round btn-secondary mx-1"><i class="ft-plus"></i> เพิ่มคำถามแบบมีตัวเลือก</button>
      </a>
      <a data-toggle="modal" data-target="#addAnswerText">
        <button class="btn btn-round btn-secondary mx-1"><i class="ft-plus"></i> เพิ่มคำถามแบบให้พิมพ์ตอบ</button>
      </a>
    @else
      <a>
        <button class="btn btn-round btn-secondary mx-1" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-plus"></i> เพิ่มแบบมีตัวเลือก</button>
      </a>
      <a>
        <button class="btn btn-round btn-secondary mx-1" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-plus"></i> เพิ่มแบบให้พิมพ์คำตอบ</button>
      </a>
    @endcan
  </div>
</div>

<div class="row justify-content-center">
  @foreach ($review as $item)
    <div class="col-12 col-md-8 col-lg-6 mb-2">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title text-center">{{ $item->title }}</h4>
        </div>
        <div class="card-body">
          @foreach ($item->questions as $question)
            <p>{{ $question['title'] }}</p>
          @endforeach
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="modal fade text-left" id="addAnswerChoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel1">แบบประเมินหลักสูตรออนไลน์</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form class="form-horizontal" action="{{ route('review_store') }}" method="POST">
        @csrf
        <input type="hidden" name="review_group_id" value="{{ $review_group_id }}">
        <input type="hidden" name="type" value="choice">
        <input type="hidden" name="status" value=1>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="user-name">Title</label>
            <input type="text" name="title" placeholder="Title" class="form-control" required>
          </div>
          <fieldset class="form-group @if($errors->course->has('choice')) danger @endif">
            <label for="user-name">Choice *</label>
            <select class="select2 form-control mb-1" name="choice_id" required>
              <option value=""> กรุณาเลือก Choice</option>
              @foreach ($choices as $item )
                <option value={{ $item->_id }}>{{ $item->title }}</option>
              @endforeach
            </select>
            @if($errors->course->has('teacher_id'))
              <span class="small" role="alert">
              <p class="mb-0">{{ $errors->course->first('teacher_id') }}</p>
              </span>
            @endif
            <div class="mt-1">
              <a href="#" data-toggle="modal" data-target="#addChoice" onclick="hideModal()">คลิกที่นี่เพื่อเพิ่ม Choice</a>
            </div>
          </fieldset>
          <div class="form-group mb-2 questions-repeater">
            <label for="user-name">คำถาม</label>
            <div data-repeater-list="questions">
              <div class="input-group mb-1" data-repeater-item>
                <input type="text" name="questions" placeholder="คำถาม" class="form-control">
                <div class="input-group-append">
                  <span class="input-group-btn" id="button-addon2">
                    <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="button" data-repeater-create class="btn btn-outline-secondary">
                <i class="ft-plus"></i> เพิ่มคำถาม
              </button>
            </div>
          </div>
          <fieldset>
            <div class="custom-control custom-checkbox mb-2">
              <input type="checkbox" class="custom-control-input" name="require" id="require">
              <label class="custom-control-label" for="require">บังคับให้ตอบ</label>
            </div>
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

<div class="modal fade text-left" id="addChoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel1">เพิ่ม Choice</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form class="form-horizontal" action="{{ route('review_choice_store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="user-name">Title</label>
            <input type="text" name="title" placeholder="Title" class="form-control" required>
          </div>
          <div class="form-group mb-2 choice-repeater">
            <label for="user-name">Choice</label>
            <div data-repeater-list="choices">
              <div class="input-group mb-1" data-repeater-item>
                <input type="text" name="choices" placeholder="Choice" class="form-control">
                <div class="input-group-append">
                  <span class="input-group-btn" id="button-addon2">
                    <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="button" data-repeater-create class="btn btn-outline-secondary">
                <i class="ft-plus"></i> เพิ่ม Choice
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปิด</button>
          <button type="submit" class="btn btn-outline-primary">บันทึก</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>
  <script>
  function handleDeleteReviewUrl(review_group_id) {
    url = "{{ route('review_group_delete') }}/"+review_group_id
    swal({
      title: "คุณต้องการลบ Review URL ใช่หรือไม่ ?",
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
  function hideModal() {
    $("[data-dismiss=modal]").trigger({ type: "click" });
  }
  // Custom Show / Hide Configurations
  $('.choice-repeater, .questions-repeater').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function(remove) {
      if (confirm('Are you sure you want to remove this item?')) {
        $(this).slideUp(remove);
      }
    }
  });
  </script>
@endsection