@extends('layouts.app')

@php $title = strtoupper('add homework'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $data->course_id, '#homework']) }}">Course</a></li>
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
      <h4 class="card-title">Homework Detail</h4>
    </div>
    <div class="card-content">
      <div class="card-body overflow-hidden">
        <form id="form" class="form" action="{{ route('homework_store') }}" method="POST">
          @csrf
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <input type="hidden" name="id" value="{{ $data->_id }}">
          <input type="hidden" name="course_id" value="{{ $data->course_id }}">
          <div class="form-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <p>คำถาม <span class="required">*</span></p>
                  <input type="hidden" name="question" />
                  <div class="controls">
                    <div id="editor">
                      {!! $data->question !!}
                    </div>
                  </div>
                  </p>
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
</div>
@endsection

@section('style')

@endsection

@section('script')
  <script>
    $('document').ready(function(){
      var quill = new Quill('#editor', {
        modules: {
          toolbar: [
            [{ header: [1, 2, false] }],
            ['bold', 'italic', 'underline', 'link'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            // ['list-ordered','list-bullet'],
            ['image']
          ]
        },
        placeholder: 'รายละเอียดคำถาม',
        theme: 'snow'  // or 'bubble'
      });
      quill.getModule("toolbar").addHandler("image", () => {
        selectLocalImage(quill);
      });
      var form = document.querySelector('form');
      form.onsubmit = function() {
        // Populate hidden form on submit
        var content = document.querySelector('input[name=question]');
        content.value = quill.container.firstChild.innerHTML
        return true;
      };
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
          image_url = "{{ env('IMG_PATH') }}"+response.message
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
