@extends('layouts.app')

@php $title = strtoupper('add course'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_index') }}">COURSE</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-12 col-md-8 mb-4">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        <h4 class="card-title">COURSE</h4>
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
          <span>COURSE INFO</span>
        </h6>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form class="form-horizontal" action="{{ route('course_store') }}" method="POST">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}">
            {{-- สำหรับ CROP IMAGE --}}
            <input type="hidden" id="img-x" name="imgX" />
            <input type="hidden" id="img-y" name="imgY" />
            <input type="hidden" id="img-height" name="imgHeight" />
            <input type="hidden" id="img-width" name="imgWidth" />
            <input type="hidden" id="img-path" name="imgPath" />
            <input type="hidden" id="img-final" name="img_final" />
            {{-- END สำหรับ CROP IMAGE --}}
            {{-- สำหรับสร้าง Folder เวลาอัพโหลดรูป --}}
              <input type="hidden" id="input-path" name="input_path" value="course" />
            {{-- สำหรับสร้าง Folder เวลาอัพโหลดรูป --}}
            <input type="hidden" id="id" name="id" value="{{ $data->_id }}" />
            {{-- thumbnail --}}
            <fieldset class="form-group floating-label-form-group @if($errors->course->has('thumbnail')) danger @endif">
              <div class="form-group">
                <div class="card border-primary text-center bg-transparent">
                  <div class="card-header text-left">
                    <label for="basicInputFile"> Thumbnail *</label>
                    <div>
                      <div class="controls">
                        <input type="file" name="thumbnail" class="form-control"  id="uploadfile" accept="image/*" >
                      </div>
                      @if(!empty($errors->course->first('thumbnail')))
                        <label class="text-danger"><small>{{ "กรุณาใส่่รูป" }}</small></label>
                      @endif
                    </div>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <div class="crop_images">
                        <img id="imageCropSrc" class="aspect-ratio-4-3 img-fluid" src="" alt="Picture">
                        <button id="btnCrop" class="btn btn-danger btn-block mt-1" type="button">Crop Images</button>
                      </div>
                      @php 
                        if($data->test_status==1) {
                          $img_thumbnail = env('IMG_PATH_TUTORME').$data->thumbnail;
                        } else {
                          $img_thumbnail = env('IMG_PATH').$data->thumbnail;
                        }
                      @endphp
                      @if(!empty($data->thumbnail))
                      <div class="final_images">
                        <img id="imageFinalSrc" class="aspect-ratio-4-3 img-fluid" src="{{ $img_thumbnail }}" name="" alt="Picture">
                      </div>
                      @else
                      <div class="final_images">
                        <img id="imageFinalSrc" class="aspect-ratio-4-3 img-fluid" src="" alt="Picture">
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
            {{-- TITLE --}}
            <fieldset class="form-group floating-label-form-group @if($errors->course->has('title')) danger @endif">
              <label for="user-name">Title</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="Title" required>
              @if($errors->course->has('title'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->course->first('title') }}</p>
                </span>
              @endif
            </fieldset>
            {{-- Requier Course --}}
            <fieldset class="form-group @if($errors->course->has('require_course')) danger @endif">
              <label for="user-name"> Require Course </label>
              <select class="select2 form-control" name="require_course">
                <option value="">ไม่ Require Course</option>
                <optgroup label="Course">
                  @foreach ($course as $item )
                    <option value={{ $item }} 
                      @if(!empty($data->require_course) && in_array($item, $data->require_course)) selected  @endif
                      >
                      {{ CourseClass::get_name_course($item) }}
                    </option>
                  @endforeach
                </optgroup>
              </select>
            </fieldset>
            {{-- Category --}}
            <fieldset class="form-group @if($errors->course->has('category_id')) danger @endif">
              <label for="user-name">Category *</label>
              <select class="select2 form-control" name="category_id">
                <option value=""> กรุณาเลือก Category</option>
                <optgroup label="Category">
                  @foreach ($category as $item )
                    <option value={{ $item }} 
                      @if(!empty($data->category_id) && ((string)$data->category_id == (string)$item)) selected  @endif
                    >{{ CourseClass::get_name_category($item) }}</option>
                  @endforeach
                </optgroup>
              </select>
              @if($errors->course->has('category_id'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->course->first('category_id') }}</p>
                  </span>
              @endif
            </fieldset>
            {{-- Type --}}
            <fieldset class="form-group @if($errors->course->has('type')) danger @endif">
              <label for="user-name">Type *</label>
              <select class="select2 form-control" name="type">
                <option value=""> กรุณาเลือก Type</option>
                  @foreach ($type as $key => $value )
                    <option value={{ $key }} 
                      @if(!empty($data->type) && ((string)$data->type == (string)$key)) selected  @endif
                    >{{ $value }}</option>
                  @endforeach
              </select>
              @if($errors->course->has('type'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->course->first('type') }}</p>
                  </span>
              @endif
            </fieldset>
            {{-- Teacher --}}
            <fieldset class="form-group @if($errors->course->has('teacher_id')) danger @endif">
              <label for="user-name">Teacher *</label>
              <select class="select2 form-control" name="teacher_id">
                <option value=""> กรุณาเลือก Teacher</option>
                <optgroup label="Teacher">
                  @foreach ($teacher as $item )
                    <option value={{ $item }} 
                      @if(!empty($data->teacher_id) && ((string)$data->teacher_id == (string)$item)) selected  @endif
                    >{{ TeacherClass::get_name_teacher($item) }}</option>
                  @endforeach
                </optgroup>
              </select>
              @if($errors->course->has('teacher_id'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->course->first('teacher_id') }}</p>
                  </span>
              @endif
            </fieldset>
            {{-- slug --}}
            <fieldset class="form-group floating-label-form-group @if($errors->course->has('slug')) danger @endif">
              <label for="user-name">Slug</label>
              <input type="text" name="slug" class="form-control" value="{{ old('slug', $data->slug) }}" placeholder="Slug" required>
              @if($errors->course->has('slug'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->course->first('slug') }}</p>
                </span>
              @endif
            </fieldset>
            {{-- Tag --}}
            <fieldset class="form-group @if($errors->course->has('tag')) danger @endif">
              <label>Tag</label>
              <input type="text" data-role="tagsinput"   class='form-control'  name="tag" value="{{ implode(',',$data->tag) }}">
            </fieldset>
            {{-- description --}}
            <fieldset class="form-group floating-label-form-group @if($errors->course->has('description')) danger @endif">
              <label for="user-name">Description</label>
              <input name="description" type="hidden">
              <div id="description">
                {!! $data->description !!}
              </div>
              @if($errors->course->has('description'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->course->first('description') }}</p>
                </span>
              @endif
            </fieldset>
            {{-- Benefits --}}
            <div class="form-group mb-2 benefits-repeater">
              <label for="user-name">ประโยชน์ต่อผู้เรียน</label>
              <div data-repeater-list="benefits">
                @if(!empty($data->benefits) && count($data->benefits) > 0)
                  @foreach ($data->benefits as $item)
                    <div class="input-group mb-1" data-repeater-item>
                      <input type="text" name="benefits" placeholder="ประโยชน์ต่อผู้เรียน" class="form-control" value="{{ $item }}">
                      <div class="input-group-append">
                        <span class="input-group-btn" id="button-addon2">
                          <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                        </span>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="input-group mb-1" data-repeater-item>
                    <input type="text" name="benefits" placeholder="ประโยชน์ต่อผู้เรียน" class="form-control">
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
                  <i class="ft-plus"></i> เพิ่มประโยชน์ต่อผู้เรียน
                </button>
              </div>
            </div>
            {{-- Appropriates --}}
            <div class="form-group mb-2 appropriates-repeater">
              <label for="user-name">เหมาสมกับผู้เรียน</label>
              <div data-repeater-list="appropriates">
                @if(!empty($data->appropriates) && count($data->appropriates) > 0)
                  @foreach ($data->appropriates as $item)
                    <div class="input-group mb-1" data-repeater-item>
                      <input type="text" name="appropriates" placeholder="เหมาสมกับผู้เรียน" class="form-control" value="{{ $item }}">
                      <div class="input-group-append">
                        <span class="input-group-btn" id="button-addon2">
                          <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                        </span>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="input-group mb-1" data-repeater-item>
                    <input type="text" name="appropriates" placeholder="เหมาสมกับผู้เรียน" class="form-control">
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
                  <i class="ft-plus"></i> เพิ่มเหมาสมกับผู้เรียน
                </button>
              </div>
            </div>
            <div>
              @can('editor')
              <button type="submit" class="btn btn-primary btn-block">SAVE</button>
              @else
              <button  type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>NOT ALLOW</button>
              @endcan
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @if($data->type=='standard')
    {{-- Episode group --}}
    @include('course.detail-episode_group')
    {{-- Episode list --}}
    @include('course.detail-episode_list')
    {{-- Homework --}}
    @include('course.detail-homework_list')
    {{-- Examination --}}
    @include('course.detail-examination_list')
  @endif
</div>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/images/cropper/cropper.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/tagsinput.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
@endsection

@section('script')
  <script src="{{ asset('js/tagsinput.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/extensions/cropper.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>
  {{-- IMAGE CROP --}}
  <script>
    var $image = $('#imageCropSrc')
    var imgX = $('#img-x')
    var imgY = $('#img-y')
    var imgHeight = $('#img-height')
    var imgWidth = $('#img-width')
    var imgPath = $('#img-path')
    var inputPath = $('#input-path')
    var image_url = ''
    var img_url = "{{  asset('/') }}"
    $('.crop_images').hide()

    @if(!empty($data->thumbnail)) 
      $('.final_images').show()
    @else
      $('.final_images').hide()
    @endif

    /***************************************
    *          Get Data Crop image         *
    ***************************************/
    // $image.cropper()

    $('.get-data').on('click', function() {
      getImgCrop()
    })

    $('#btnSubmit').on('click', function() {
      getImgCrop()
      // $('#form').submit()
    })

    $("#uploadfile").change(function() {
      uploadfile()
    })

    $("#btnCrop").on('click', function() {
      cropImage()
    })

    // Upload file
    function uploadfile() {
      var url = "{{ route('upload_images') }}"
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
      var file_data = $('#uploadfile').prop('files')[0]
      var formData = new FormData()

      formData.append('_token', CSRF_TOKEN)
      formData.append('file', file_data)
      formData.append('input_path', inputPath.val())
      $.ajax({
        method: 'post',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        enctype: 'multipart/form-data',
        url: url,
        success: function (response) {
          image_url = response.message
          readURLUpload(img_url+image_url)
        },
        error: function(data)
        {
          console.log(data)
        }
      })
    }

    // Crop & Upload image
    function cropImage() {
      getImgCrop()
      var url = "{{ route('upload_cropimages') }}"
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content')
      var file_data = $('#uploadfile').prop('files')[0]
      var formData = new FormData()

      formData.append('_token', CSRF_TOKEN)
      formData.append('images', image_url)
      formData.append('imgX', imgX.val())
      formData.append('imgY', imgY.val())
      formData.append('imgWidth', imgWidth.val())
      formData.append('imgHeight', imgHeight.val())
      formData.append('input_path', inputPath.val())
      $.ajax({
        method: 'post',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        enctype: 'multipart/form-data',
        url: url,
        success: function (response) {
          // console.log(response)
          readURLFinal(response.message)
        },
        error: function(data)
        {
          console.log(data)
        }
      })
    }

    function getImgCrop() {
      if(document.getElementById('imageCropSrc').getAttribute('src')) {
        result = $image.cropper("getData")
        imgX.val(result.x)
        imgY.val(result.y)
        imgWidth.val(result.width)
        imgHeight.val(result.height)
      } 
    }

    function readURLUpload(url) {
      $('.final_images').hide()
      $('.crop_images').show()
      $('#imageCropSrc').attr('src', url)
      handleCrop(url)
    }

    function readURLFinal(url) {
      $('#imageFinalSrc').attr('src', img_url+url)
      $('#img-final').val(url)
      $('.crop_images').hide()
      $('.final_images').show()
    }

    function handleCrop(url) {
      $('#imageCropSrc').cropper('destroy').cropper({
        viewMode: 1,
        aspectRatio: 16/9,
        autoCropArea: 1,
        restore: false,
        zoomOnWheel: false
      })
    }
  </script>
  
  <script>
    var quill_desc = new Quill('#description', {
      theme: 'snow'
    });
    //  Desc
    var form_desc = document.querySelector('form');
    form_desc.onsubmit = function() {
      // Populate hidden form on submit
      var description = document.querySelector('input[name=description]');
      description.value = quill_desc.container.firstChild.innerHTML

      return true;
    };
    quill_desc.on('text-change', function (delta, oldDelta, source) {
      var editor = $(".ql-editor").html();
      var pattern = /&lt;/g
      if(editor.match(pattern)) {
        editor = editor.replace(/&lt;/g, '<')
        editor = editor.replace(/&gt;/g, '>')
        editor = editor.replace(/&nbsp;/g, ' ')
        var description = document.querySelector('input[name=description]');
        quill_desc.container.firstChild.innerHTML = editor
        description.value = editor
      }
    });
  </script>

  <script>
  // Custom Show / Hide Configurations
  $('.appropriates-repeater, .benefits-repeater').repeater({
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
