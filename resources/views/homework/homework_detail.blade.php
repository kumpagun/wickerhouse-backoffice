@extends('layouts.app')

@php $title = strtoupper('add homework'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $course_id, '#homework']) }}">Course</a></li>
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
          <div class="form-body">
            {{-- Course --}}
            <fieldset class="form-group @if($errors->course->has('course_id')) danger @endif">
              <label for="user-name">Course *</label>
              <select class="select2 form-control" name="course_id">
                <option value=""> กรุณาเลือก Course</option>
                <optgroup label="Course">
                  @foreach ($courses as $item )
                    <option value={{ $item }} 
                      @if(!empty($data->course_id) && ((string)$data->course_id == (string)$item)) selected  @endif
                    >{{ CourseClass::get_name_course($item) }}</option>
                  @endforeach
                </optgroup>
              </select>
              @if($errors->course->has('course_id'))
                <span class="small" role="alert">
                <p class="mb-0">{{ $errors->course->first('course_id') }}</p>
                </span>
              @endif
            </fieldset>
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
    // var quill = new Quill('#editor', {
    //   theme: 'snow'
    // });
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
    var form = document.querySelector('form');
    form.onsubmit = function() {
      // Populate hidden form on submit
      var content = document.querySelector('input[name=question]');
      content.value = quill.container.firstChild.innerHTML
      return true;
    };
    quill.on('text-change', function (delta, oldDelta, source) {
      var editor = $(".ql-editor").html();
      var pattern = /&lt;/g
      if(editor.match(pattern)) {
        editor = editor.replace(/&lt;/g, '<')
        editor = editor.replace(/&gt;/g, '>')
        editor = editor.replace(/&nbsp;/g, ' ')
        var content = document.querySelector('input[name=question]');
        quill.container.firstChild.innerHTML = editor
        content.value = editor
      }
    });
  </script>
@endsection
