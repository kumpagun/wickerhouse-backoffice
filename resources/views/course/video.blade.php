@php $title="เพิ่มวิดีโอ"; @endphp

@extends('layouts.main')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/tags/tagging.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/listbox/bootstrap-duallistbox.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/dual-listbox.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
<style>
  #preview_thumbnail {
    margin-bottom: 10px;
    width: 300px;
    height: 169px;
  }
  #display-upload {
    position: absolute;
    left: 2.5%;
  }
  .progress {
    height: 1.5em;
  }
  .progress.progress-success {
    transition: width .6s ease;
  }
  .select2 {
    width: 100% !important;
  }
</style>
@endsection

@section('script')
<script src="{{ asset('jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('jQuery-File-Upload/js/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload.js') }}"></script>

<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/tags/tagging.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/ui/prism.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/tags/tagging.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/listbox/jquery.bootstrap-duallistbox.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/listbox/form-duallistbox.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  })

  $('textarea').ckeditor();
  $('.published_at').datetimepicker({
    format: 'DD-MM-YYYY HH:mm',
  })
  $( document ).ready(function() {
    var preview = $('#preview_thumbnail').attr('src');
    if(!preview) {
      readURL()
    }
    $("[name='timestamp']").val(new Date().getTime())
    $('#fileupload').fileupload({
      url: '{{route("video_upload_file")}}',
      maxChunkSize: 100 * 1024 * 1024,
      dataType: 'json',
      add: function (e, data) {
        var originalFilename = data.files[0].name
        var splitName = originalFilename.split('.')
        var length = splitName.length
        var formatExtension = '.'+splitName[length-1]
        var fileName = $("[name='file_name']").val()
        // if have exist file
        $('#display-upload').text('Uploading 0%').removeClass('text-success')
        $('.progress-bar').attr('value', 0)
        data.submit();
        $('#cancel-btn').removeClass('hidden')
        $('#cancel-btn').click(function (e) {
          data.abort();
        })
        $("[name='file_name']").val($("[name='timestamp']").val()+formatExtension)
      },
      done: function (e, data) {
        var fileType = data.files[0].type
        $("[name='timestamp']").val(new Date().getTime())
        $('#display-upload').fadeIn().text('Upload Success')
        // $('#display-upload').fadeIn().text('Upload Success').addClass('text-success')
        $('#submit-btn').attr('disabled', false)
        $('#cancel-btn').addClass('hidden')
        $('#delete-btn').removeClass('hidden')
        $('#preview-video').removeClass('hidden')
        
        var filename = $("[name='file_name']").val()
        // player.dispose()
        // $('#preview-video').append('<video id="test-videojs" class="video-js"></video>')
        // player = videojs('test-videojs', {
        //   controls: true,
        //   preload: 'auto',
        //   loop: true,
        //   autoplay: false,
        //   sources: [{
        //     src: path + filename
        //   }],
        //   poster: ''
        // });
        // player.on('ready', function (e){
        //   console.log(e)
        // })
      },
      progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#display-upload').fadeIn().text('Uploading '+ progress+ ' %')
        $('.progress-bar').attr(
          'value',
          progress
        );
        $('.progress-bar').attr(
          'aria-valuenow',
          progress
        );
        $('.progress-bar').attr(
          'aria-valuemin',
          progress
        );
        $('.progress-bar').attr(
          'style',
          'width:'+progress+'%'
        );
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (errorThrown == 'abort') {
          $('#cancel-btn').addClass('hidden')
          $('#display-upload').text('').removeClass('text-success')
          $('#progress progress.progress ').attr('value', 0)
          // deleteVideo()
          $("[name='timestamp']").val(new Date().getTime())
          $("[name='file_name']").val('')
          $('#submit-btn').attr('disabled', true)
        }
      }
    })
  })
  function readURL(input) {
    if (input && input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#preview_thumbnail').attr('src', e.target.result);
      }
      if(input.files[0].size > 1000000){
        swal.fire({
            title: "Image Thumnail",
            text: "ไฟล์ขนาดเกิน 1 mb",
            type: "warning",
            confirmButtonColor: "#FF6275",
          })
        this.value = "";
        reader.readAsDataURL(input.files[0]);
        $('#preview_thumbnail').hide()
      }else{
        reader.readAsDataURL(input.files[0]);
        $('#preview_thumbnail').show()
      }
    } else {
      $('#preview_thumbnail').hide()
      
    }
  }
  $('#delete-btn').click(function () {
    $('#delete-btn').attr('disabled', true)
    deleteVideo()
  })
  function deleteVideo() {
    var fileName = $("[name='file_name']").val()
    if (fileName) {
      $.ajax({
        type: 'POST',
        url: "{{route('video_delete_file')}}",
        data: {
          _token: "{{ csrf_token() }}",
          file: fileName
        }
      })
      .done(function (data) {
        if (data.status == 'DELETE_SUCCESS') {
          $('#delete-btn').attr('disabled', false)
          $('#delete-btn').addClass('hidden')
  
          $("[name='timestamp']").val(new Date().getTime())
          $("[name='file_name']").val('')
          $('#submit-btn').attr('disabled', true)
          $('#display-upload').text('').removeClass('text-success')
          $('#progress progress.progress ').attr('value', 0)
          $('#preview-video').addClass('hidden')
          $('#preview-video').children().remove()
          $('.progress-bar').attr(
          'value',
             0
          );
          $('.progress-bar').attr(
            'aria-valuenow',
            0
          );
          $('.progress-bar').attr(
            'aria-valuemin',
            0
          );
          $('.progress-bar').attr(
            'style',
            'width:'+0+'%'
          );
        }
      })
    }
  }

  $("#inputGroupFile01").change(function() {
    readURL(this);
  });
</script>
@endsection

@section('title')
{{ strtoupper($title) }}
@endsection

@section('breadcrumb')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{route('video_list')}}">หน้าแรก</a></li>
  <li class="breadcrumb-item active">{{ $title }}</li>
</ol>
@endsection

@section('content')
<section class="row">
  <div class="col-sm-12">
    <!-- Kick start -->
    <div id="kick-start" class="card">
      <div class="card-content collapse show">
        <div class="card-body">
          <form class="form" method="POST" action="{{ URL::route('video_store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-body">
              @if(!empty($data))
                <input name="video_id" type="hidden" value="{{ $data->_id }}" />
              @endif
              <input type="hidden" name="timestamp" value="">
              <input type="hidden" class="form-control" name="file_name" value="{{old('file_name')}}" readonly>
              {{-- Video --}}
              <h4 class="form-section"> Video</h4>
              @php
                if(!empty($errors->video->first('files'))) {
                  $style = "-danger";
                  $error = "danger";
                } else {
                  $style = "-normal";
                  $error = "normal";
                }
              @endphp
              <fieldset class="form-group {{$error}} mb-0">
                <label class="text{{$style}}" for="basicInputFile">Upload video file. <span class="text-danger">*</span></label>
                <div class="custom-file">
                  <input id="fileupload" type="file" name="files[]" accept=".mp4,.mov,.avi,.mpg">
                  <label class="custom-file-label" for="fileupload">Choose file</label>
                </div>
                <button type="button" class="btn btn-danger btn-min-width mt-1 hidden" id="cancel-btn">Cancel Upload</button>
                <button type="button" class="btn btn-danger btn-min-width mt-1 @if(empty(old('file_name'))) hidden @endif" id="delete-btn">Delete File</button>
                <p class="mb-0"><small class="text-muted">ชนิดของไฟล์ที่รองรับ mp4, mov, avi, mpg (แนะนำให้ใช้ mp4 และเข้ารหัสด้วย H.264/ACC)</small></p>
                @if(!empty($errors->video->first('files')))
                  <label class="text-danger mb-0"><small>{{ "กรุณาอัพโหลดไฟล์วิดีโอ" }}</small></label>
                @endif
              </fieldset>
              <div class="form-group mt-1">
                <div class="progress">
                  @php
                  $progress_value = 0;
                  $progress_text = '';
                  if (!empty(old('file_name'))) {
                    $progress_value = 100;
                    $progress_text = 'Upload Success';
                  }
                  @endphp
                  <div class="text-xs-center @if(!empty($progress_text)) text-success @endif" id="display-upload">{{ $progress_text }}</div>
							    <div class="progress-bar" role="progressbar" aria-valuenow="{{$progress_value}}" aria-valuemin="{{$progress_value}}" aria-valuemax="100" style="width:{{$progress_value}}%" aria-describedby="example-caption-2"></div>
                </div>
              </div>
              {{-- Thumbnail --}}
              <h4 class="form-section"> Thumbnail</h4>
              <div class="row flex-end">
                <div class="col-12 flex-end flex-wrap">
                  @if(!empty($data->upload_thumbnail))
                    <img id="preview_thumbnail" src="{{ asset('storage/'.$data->upload_thumbnail) }}"  alt="Thumbnail" />
                  @else
                    <img id="preview_thumbnail" src="" alt="Thumbnail" />
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    @php
                      if(!empty($errors->video->first('upload_thumbnail'))) {
                        $style = "-danger";
                        $error = "error";
                      } else {
                        $style = "-normal";
                        $error = "";
                      }
                    @endphp
                    <div class="form-group {{$error}}">
                      <label class="text{{$style}}" for="basicInputFile">Thumbnail <span class="text-danger">*</span></label>
                      <div class="custom-file danger">
                        <input type="file" name="upload_thumbnail"  class="form-control"  id="inputGroupFile01" accept="image/*">
                      </div>
                      <p class="mb-0"><small>* ขนาดไฟล์ไม่เกิน 1 mb , ขนาด 952 x 536 ( สัดส่วน 16:9 )</small></p>
                      @if(!empty($errors->video->first('upload_thumbnail')))
                        <label class="text-danger mb-0"><small>{{ "กรุณาใส่รูป" }}</small></label>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              
              <h4 class="form-section"> Video Info</h4>
              <div class="row">
                {{-- Title --}}
                <div class="col-md-12">
                  @php
                    if(!empty($errors->video->first('title'))) {
                      $style = "-danger";
                    } else {
                      $style = "-normal";
                    }
                  @endphp
                  <div class="form-group">
                    <label for="userinput5" class="text{{$style}}"> Title <span class="text-danger">*</span></label>
                    <input name="title" class="form-control border{{ $style }}" type="text" placeholder="หัวข้อ" value="{{old('title', $data->title)}}">
                    @if(!empty($errors->video->first('content')))
                      <label class="text-danger mb-0"><small>{{ "กรุณาใส่หัวข้อ" }}</small></label>
                    @endif
                  </div>
                </div>
              </div>
              {{-- Type, Published --}}
              <div class="row">
                {{-- Type --}}
                <div class="col-md-6">
                  @php
                  if(!empty($errors->video->first('type'))) {
                    $style = "-danger";
                    $error = "danger";
                  } else {
                    $style = "-normal";
                    $error = "normal";
                  }
                  @endphp
                  <div class="form-group">
                      <label class="text{{$style}}" for="userinput8" >ประเภทวิดีโอ <span class="text-danger">*</span></label>
                    <div class="controls">
                      <select  name="type" class="select2-border border-warning form-control" id="border-select" data-border-color="{{$error}}" data-border-variation="darken-2" data-text-color="{{$error}}" data-text-variation="darken-3">
                        <option value="">{{ "กรุณาเลือกประเภท" }}</option>
                        @foreach ($type as $key => $value)
                          <option @if($data->type == $key) selected @endif value="{{$key}}" >{{$value}}</option>
                        @endforeach
                      </select>
                      @if(!empty($errors->video->first('type')))
                      <br/>
                      <label class="text-danger"><small>{{ "กรุณาเลือกประเภท" }}</small></label>
                      @endif
                    </div>
                  </div>
                </div>
                {{-- Published --}}
                <div class="col-md-6">
                  <div class="form-group">
                    @php
                      if(!empty($errors->video->first('published_at'))) {
                        $style = "-danger";
                      } else {
                        $style = "-normal";
                      }
                    @endphp
                    <label for="userinput5" class="text{{$style}}">Published Date <span class="text-danger">*</span></label>
                    <div class='input-group date published_at'  id='datetimepicker'>
                      <input type='text' class="form-control published_at border{{ $style }}" name="published_at" value="{{ old('published_at',$data->published_at) }}" /> 
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <span class="fa fa-calendar"></span>
                        </span>
                      </div>
                    </div>
                    @if(!empty($errors->video->first('published_at')))
                    <label class="text-danger mb-0"><small>{{ "กรุ\ณาใส่วันที่" }}</small></label>
                    @endif
                  </div>
                </div>
              </div>
              {{-- League, Round, Region --}}
              <div class="row">
                {{-- League --}}
                <div class="col-md-4">
                  @php
                  if(!empty($errors->video->first('league'))) {
                    $style = "-danger";
                    $error = "danger";
                  } else {
                    $style = "-normal";
                    $error = "normal";
                  }
                  @endphp
                  <div class="form-group">
                      <label class="text{{$style}}" for="userinput8" >League <span class="text-danger">*</span></label>
                    <div class="controls">
                      <select  name="league" class="select2-border border-warning form-control" id="border-select" data-border-color="{{$error}}" data-border-variation="darken-2" data-text-color="{{$error}}" data-text-variation="darken-3">
                          <option value="">{{ "กรุณาเลือก league" }}</option>
                          @foreach ($league as $key => $value)
                              <option @if($data->league_id == $key) selected @endif value="{{$key}}" >{{$value}}</option>
                          @endforeach
                      </select>
                      @if(!empty($errors->video->first('league')))
                        <br/>
                        <label class="text-danger mb-0"><small>{{ "กรุณาเลือก league" }}</small></label>
                      @endif
                    </div>
                  </div>
                </div>
                {{-- Round --}}
                <div class="col-md-4">
                  @php
                    if(!empty($errors->video->first('round'))) {
                      $style = "-danger";
                      $error = "danger";
                    } else {
                      $style = "-normal";
                      $error = "normal";
                    }
                  @endphp
                  <div class="form-group">
                    <label class="text{{$style}}" for="userinput8" >Round</label>
                    <div class="controls">
                      <select  name="round" class="select2-border border-warning form-control" id="border-select" data-border-color="{{$error}}" data-border-variation="darken-2" data-text-color="{{$error}}" data-text-variation="darken-3" onchange="handleLeague(this.value)">
                        <option value="">{{ "เลือก round" }}</option>
                        @foreach ($round as $key => $value)
                            <option @if($data->round_id == $key) selected @endif value="{{$key}}" >{{$value}}</option>
                        @endforeach
                      </select>
                      @if(!empty($errors->video->first('round')))
                        <label class="text-danger mb-0"><small>{{ "กรุณาใส่เลือก Round" }}</small></label>
                      @endif
                    </div>
                  </div>
                </div>
                {{-- Region --}}
                <div class="col-md-4">
                  @php
                  if(!empty($errors->video->first('region'))) {
                    $style = "-danger";
                    $error = "danger";
                  } else {
                    $style = "-normal";
                    $error = "normal";
                  }
                  @endphp
                  <div class="form-group">
                    <label class="text{{$style}}" for="userinput8" >Region</label>
                    <div class="controls">
                      <select  name="region" class="select2-border border-warning form-control" id="border-select" data-border-color="{{$error}}" data-border-variation="darken-2" data-text-color="{{$error}}" data-text-variation="darken-3" onchange="handleLeague(this.value)">
                        <option value="">{{ "เลือก league" }}</option>
                        @foreach ($region as $key => $value)
                            <option @if($data->region_id == $key) selected @endif value="{{$key}}" >{{$value}}</option>
                        @endforeach
                      </select>
                      @if(!empty($errors->video->first('region')))
                        <label class="text-danger mb-0"><small>{{ "กรุณาใส่เลือก Region" }}</small></label>
                      @endif
                    </div>
                  </div>
                </div>
              </div>  
              {{-- Content --}}
              <div class="row">
                <div class="col-md-12">
                  @php
                    if(!empty($errors->video->first('content'))) {
                      $style = "-danger";
                    } else {
                      $style = "-normal";
                    }
                  @endphp
                  <div class="form-group">
                    <label for="userinput8" class="text{{$style}}">Content</label>
                    <textarea name="content" id="userinput8" rows="5" class="form-control border" placeholder="เนื้อหา">{{ old('content', $data->content) }}</textarea>
                    @if(!empty($errors->video->first('content')))
                    <label class="text-danger mb-0"><small>{{ "กรุณาใส่ข้อมูล" }}</small></label>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="form-actions right">
              <a class="btn btn-outline-secondary btn-min-width mr-1" href="{{ route('list_news')}}">ยกเลิก</a>
              <button type="submit" class="btn btn-secondary btn-min-width">
                บันทึก
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--/ Kick start -->
  </div>
</section>
@endsection
