@extends('layouts.app')

@php $title = 'เพิ่มแบบทดสอบ' @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $examination_group->course_id, '#examination']) }}">Course</a></li>
      <li class="breadcrumb-item"><a href="{{ route('examination_index', ['id' => $examination_group->_id]) }}">{{ strtoupper(implode(',',$examination_group->type)) }}</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center skin skin-flat">
  <div class="col-12 col-md-10 col-xl-8">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="card-content">
        <div class="card-body overflow-hidden">
          <form id="form" class="form" action="{{ route('examination_store') }}" method="POST">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <input type="hidden" name="examination_group_id" value="{{ $examination_group->_id }}">
            <input type="hidden" name="id" value="{{ $examination->_id }}">
            <div class="form-body">
              {{-- QUESTION --}}
              <fieldset class="form-group floating-label-form-group">
                <label for="user-name">Question</label>
                <input name="question" type="hidden">
                <div id="question">
                  {!! $examination->question !!}
                </div>
              </fieldset>
              <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                <span>CHOICE</span>
              </h6>
              {{-- CHOICE 0 --}}
              @php 
                $choice_number = [0,1,2,3];
              @endphp
              @foreach ($choice_number as $number)
                <fieldset class="form-group floating-label-form-group mb-3">
                  <input name="choice_{{$number}}" type="hidden">
                  <input type="radio" name="answer_key" id="answer-{{$number}}" value={{$number}} @if($examination->answer_key==$number) checked @endif>
                  <label for="answer-{{$number}}">{{ $number + 1 }}.</label>
                  <div id="choice_{{$number}}">{!! $examination->choice[$number]['title'] !!}</div>
                </fieldset>
              @endforeach
            </div>
            <div>
              <button type="submit" class="btn btn-block btn-primary">SAVE</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
@endsection

@section('script')
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
<script>
  $('document').ready(function(){ 
    var quill_question = new Quill('#question', {
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['image']
        ]
      },
      theme: 'snow'  // or 'bubble'
    });
    quill_question.getModule("toolbar").addHandler("image", () => {
      selectLocalImage(quill_question);
    });
    
    var quill_choice_0 = new Quill('#choice_0', {
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['image']
        ]
      },
      theme: 'snow'
    });
    quill_choice_0.getModule("toolbar").addHandler("image", () => {
      selectLocalImage(quill_choice_0);
    });
    
    var quill_choice_1 = new Quill('#choice_1', {
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['image']
        ]
      },
      theme: 'snow'
    });
    quill_choice_1.getModule("toolbar").addHandler("image", () => {
      selectLocalImage(quill_choice_1);
    });
    
    var quill_choice_2 = new Quill('#choice_2', {
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['image']
        ]
      },
      theme: 'snow'
    });
    quill_choice_2.getModule("toolbar").addHandler("image", () => {
      selectLocalImage(quill_choice_2);
    });
    
    var quill_choice_3 = new Quill('#choice_3', {
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline'],
          ['image']
        ]
      },
      theme: 'snow'
    });
    quill_choice_3.getModule("toolbar").addHandler("image", () => {
      selectLocalImage(quill_choice_3);
    });
    var form = document.querySelector('form');
    form.onsubmit = function() {
      // Populate hidden form on submit
      var radios = document.getElementsByName('answer_key');
      var question = document.querySelector('input[name=question]');
      var choice_0 = document.querySelector('input[name=choice_0]');
      var choice_1 = document.querySelector('input[name=choice_1]');
      var choice_2 = document.querySelector('input[name=choice_2]');
      var choice_3 = document.querySelector('input[name=choice_3]');
      question.value = quill_question.container.firstChild.innerHTML
      choice_0.value = quill_choice_0.container.firstChild.innerHTML
      choice_1.value = quill_choice_1.container.firstChild.innerHTML
      choice_2.value = quill_choice_2.container.firstChild.innerHTML
      choice_3.value = quill_choice_3.container.firstChild.innerHTML
      var answer_key 
      for (var i = 0, length = radios.length; i < length; i++) {
        if (radios[i].checked) {
          answer_key = radios[i].value
          break;
        }
      }

      if(!question || !choice_0 || !choice_1 || !choice_2 || !choice_3 || !answer_key) {
        swal('กรุณากรอกข้อมูลให้ครบ')
        return false
      }

      if(quill_question.container.firstChild.innerHTML==="<p><br></p>") {
        swal('กรุณากรอกข้อมูลให้ครบ') 
        return false 
      }
      if(quill_choice_0.container.firstChild.innerHTML==="<p><br></p>") { 
        swal('กรุณากรอกข้อมูลให้ครบ') 
        return false 
      }
      if(quill_choice_1.container.firstChild.innerHTML==="<p><br></p>") { 
        swal('กรุณากรอกข้อมูลให้ครบ') 
        return false 
      }
      if(quill_choice_2.container.firstChild.innerHTML==="<p><br></p>") { 
        swal('กรุณากรอกข้อมูลให้ครบ') 
        return false 
      }
      if(quill_choice_3.container.firstChild.innerHTML==="<p><br></p>") { 
        swal('กรุณากรอกข้อมูลให้ครบ') 
        return false 
      }

      // document.getElementById("form").submit(); 

      return true;
    };
  })
</script>
<script>
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
