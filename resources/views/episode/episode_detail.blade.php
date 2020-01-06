@extends('layouts.app')

@php $title = strtoupper('add episode'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $course_id, '#episodelist']) }}">Course</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-10 col-md-8">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
          <span>EPISODE INFO</span>
        </h6>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form class="form-horizontal" action="{{ route('episode_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="course_id" value="{{ $course_id }}">
            <input type="hidden" name="timestamp" value="">
            <input type="hidden" class="form-control" name="file_name" value="{{old('file_name')}}" readonly>
            {{-- VIDEO --}}
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
            {{-- TITLE --}}
            <fieldset class="form-group floating-label-form-group @if($errors->episode->has('title')) danger @endif">
              <label for="user-name">Title</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="Title" required>
              @if($errors->episode->has('title'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->episode->first('title') }}</p>
                </span>
              @endif
            </fieldset>
            {{-- Requier Episode --}}
            <fieldset class="form-group @if($errors->course->has('require_course')) danger @endif">
              <label for="user-name"> Require Episode </label>
              <select class="select2 form-control" name="require_course">
                <option value="">ไม่ Require Episode</option>
                <optgroup label="Course">
                  @foreach ($episode_list as $item )
                    <option value={{ $item->_id }} 
                      @if(!empty($data->require_episode) && in_array($item->_id, $data->require_episode)) selected  @endif
                      >
                      {{ $item->title }}
                    </option>
                  @endforeach
                </optgroup>
              </select>
            </fieldset>
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
@endsection

@section('style')
<style>
#display-upload {
  position: absolute;
  padding: 0 5px;
}
.progress {
  height: 1.5em;
}
.progress.progress-success {
  transition: width .6s ease;
}
</style>
@endsection

@section('script')
<script src="{{ asset('jQuery-File-Upload/js/vendor/jquery.ui.widget.js') }}"></script>
<script src="{{ asset('jQuery-File-Upload/js/jquery.iframe-transport.js') }}"></script>
<script src="{{ asset('jQuery-File-Upload/js/jquery.fileupload.js') }}"></script>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})
$( document ).ready(function() {
  $("[name='timestamp']").val(new Date().getTime())
  $('#fileupload').fileupload({
    url: '{{ route("episode_upload_file") }}',
    maxChunkSize: 100 * 1024 * 1024,
    dataType: 'json',
    add: function (e, data) {
      console.log('add')
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
      console.log('done')
      var fileType = data.files[0].type
      $("[name='timestamp']").val(new Date().getTime())
      $('#display-upload').fadeIn().text('Upload Success')
      // $('#display-upload').fadeIn().text('Upload Success').addClass('text-success')
      $('#submit-btn').attr('disabled', false)
      $('#cancel-btn').addClass('hidden')
      $('#delete-btn').removeClass('hidden')
      $('#preview-video').removeClass('hidden')
      
      var filename = $("[name='file_name']").val()
    },
    progressall: function (e, data) {
      console.log('progressall')
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
function deleteVideo() {
  var fileName = $("[name='file_name']").val()
  if (fileName) {
    $.ajax({
      type: 'POST',
      url: "{{route('episode_video_delete_file')}}",
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
</script>
@endsection
