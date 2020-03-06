@extends('layouts.app')

@php $title = strtoupper('เพิ่ม,แก้ไข แบนเนอร์'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('category_index') }}">แบนเนอร์</a></li>
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
      <h4 class="card-title">รายละเอียดแบนเนอร์</h4>
    </div>
    <div class="card-content">
      <div class="card-body overflow-hidden">
        <form id="form" class="form" action="{{ route('banner_store') }}" method="POST">
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
          <input type="hidden" id="input-path" name="input_path" value="banner" />

          {{-- สำหรับสร้าง Folder เวลาอัพโหลดรูป --}}
          <input type="hidden" id="id" name="id" value="{{ $data->_id }}" />
          <input type="hidden" id="position" name="position" value="{{ $data->position }}" />

          <div class="form-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <div class="card border-primary text-center bg-transparent mt-1 px-3">
                    <div class="card-header text-left">
                      <label for="basicInputFile">รูปแบนเนอร์ *</label>
                      <div>
                        <div class="controls">
                          <input type="file" name="image_path" class="form-control"  id="uploadfile" accept="image/*" >
                        </div>
                        @if(!empty($errors->content->first('images')))
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
                        @if(!empty($data->image_path))
                          @php 
                            $img_thumbnail = config('app.url').'storage/'.$data->image_path;
                          @endphp
                        <div class="final_images mb-1">
                          <img id="imageFinalSrc" class="aspect-ratio-4-3 img-fluid" src="{{ $img_thumbnail }}" name="" alt="Picture">
                        </div>
                        
                        @else
                        <div class="final_images mb-1">
                          <img id="imageFinalSrc" class="aspect-ratio-4-3 img-fluid" src="" alt="Picture">
                        </div>
                        @endif
                        <span class="text-warning">* ขนาดที่แนะนำ 1938 x 300</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-12 mt-2 text-right">
                @can('editor')
                <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">บันทึก</button>
                @else
                <button type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>NOT ALLOW</button>
                @endcan
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
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/images/cropper/cropper.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/validation/form-validation.css') }}">
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/vendors/js/extensions/cropper.min.js') }}" type="text/javascript"></script>
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

    @if(!empty($data->image_path)) 
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
        aspectRatio: 6/1,
        autoCropArea: 1,
        restore: false,
        zoomOnWheel: false
      })
    }
  </script>
@endsection
