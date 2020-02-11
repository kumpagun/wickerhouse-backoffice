@extends('layouts.app')

@php $title = 'เพิ่ม, แก้ไข'; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => (string)$review_group->course_id, '#review']) }}">Course</a></li>
      <li class="breadcrumb-item"><a href="{{ route('review_index', ['review_group_id' => $review_group_id]) }}">{{ $review_group->title }}</a></li>
      <li class="breadcrumb-item active">{!! $title !!}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center mb-4">
  <div class="col-12 col-md-8 col-lg-6 mb-2">
    <div class="card">
      <form class="form-horizontal" action="{{ route('review_store') }}" method="POST">
        @csrf
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $data->_id }}">
        <input type="hidden" name="review_group_id" value="{{ $review_group_id }}">
        <input type="hidden" name="course_id" value="{{ $review_group->course_id }}">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="status" value=1>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="user-name">Title</label>
            <input name="title" type="hidden" class="form-control" value="{{$data->title}}" required>
            <div id="title">{!! $data->title !!}</div>
          </div>
          @if($type=='choice')
          <fieldset class="form-group @if($errors->course->has('choice')) danger @endif">
            <label for="user-name">Choice *</label>
            <select class="select2 form-control mb-1" name="choice_id" required>
              <option value=""> กรุณาเลือก Choice</option>
              @foreach ($choices as $item )
                <option value={{ $item->_id }} @if((string)$data->choice_id==$item->_id) selected @endif >{{ $item->title }}</option>
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
              @if(!empty($data->questions) && count($data->questions) > 0)
                @foreach ($data->questions as $item)
                  <div class="input-group mb-1" data-repeater-item>
                    <input type="text" name="questions" placeholder="คำถาม" class="form-control" value="{{ $item }}">
                    <div class="input-group-append">
                      <span class="input-group-btn" id="button-addon2">
                        <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                      </span>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="input-group mb-1" data-repeater-item>
                  <input type="text" name="questions" placeholder="คำถาม" class="form-control">
                  <div class="input-group-append">
                    <span class="input-group-btn" id="button-addon2">
                      <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                    </span>
                  </div>
                </div>
              @endif
            </div>
            <div class="text-center">
              <button type="button" data-repeater-create class="btn btn-outline-secondary">
                <i class="ft-plus"></i> เพิ่มคำถาม
              </button>
            </div>
          </div>
          @endif
          <fieldset>
            <div class="custom-control custom-checkbox mb-2">
              <input type="checkbox" class="custom-control-input" name="require" id="require" @if($data->require) checked @endif>
              <label class="custom-control-label" for="require">บังคับให้ตอบ</label>
            </div>
          </fieldset>
        </div>
        <div class="modal-footer">
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
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
{{-- เพิ่มข้อมูล --}}
@endsection

@section('style')
  <style>
    p {
      margin-bottom: 0;
    }
    img {
      max-width: 100%;
    }
    .question__choice {
      display: flex;
      justify-content: space-between;
    }
  </style>
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>
  <script>
  function handleDelete(review_id) {
    url = "{{ route('review_delete') }}/"+review_id
    swal({
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
  <script>
    $('document').ready(function(){
      var quill = new Quill('#title', {
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline'],
            ['image']
          ]
        },
        theme: 'snow'  // or 'bubble'
      });
      quill.getModule("toolbar").addHandler("image", () => {
        selectLocalImage(quill);
      });
      var form = document.querySelector('form');
      form.onsubmit = function() {
        var title = document.querySelector('input[name=title]');
        title.value = quill.container.firstChild.innerHTML
        if(!title) {
          swal('กรุณากรอกข้อมูลให้ครบ')
          return false
        }
        if(quill.container.firstChild.innerHTML==="<p><br></p>") {
          swal('กรุณากรอกข้อมูลให้ครบ') 
          return false 
        }
        return true;
      }
    })
    
    function selectLocalImage(quill) {
      console.log('selectLocalImage')
      var input = document.createElement("input");
      input.setAttribute("type", "file");
      input.click();
      // Listen upload local image and save to server
      input.onchange = () => {
        const file = input.files[0];
        // file type is only image.
        if (/^image\//.test(file.type)) {
          this.saveToServer(quill, file, "image");
        } else {
          console.warn("Only images can be uploaded here.");
        }
      };
    }
  
    function saveToServer(quill, file) {
      var url = "{{ route('upload_images') }}"
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
      var formData = new FormData()
      formData.append('_token', CSRF_TOKEN)
      formData.append('file', file)
      formData.append('input_path', 'quill')
      $.ajax({
        method: 'post',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        enctype: 'multipart/form-data',
        url: url,
        success: function (response) {
          image_url = "{{ config('app.url') }}"+response.message
          insertToEditor(quill,image_url)
          // console.log(response)
        },
        error: function(data)
        {
          console.log(data)
        }
      })
    }
  
    function insertToEditor(quill, url) {
      // push image url to editor.
      const range = quill.getSelection();
      quill.insertEmbed(range.index, "image", url);
    }
  </script>
@endsection