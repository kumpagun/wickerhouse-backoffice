@extends('layouts.app')

@php $title = strtoupper('add ClassRoom'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('training_index') }}">Training List</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-lg-12 ">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        {{-- <div class="card-title text-center">
          <img src="{{ asset('stack-admin/app-assets/images/logo/stack-logo-dark.png') }}" alt="branding logo">
        </div> --}}
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3">
          <span>CLASSROOM INFO</span>
        </h6>
      </div>
      <div class="card-content ">
        <div class="card-body py-0 ">
          <form class="form-group row" action="{{ route('training_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="col-12">
              <fieldset class="form-group @if($errors->classroom->has('title')) danger @endif">
              <label for="user-name">Classroom Title *</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="classroom title">
              @if($errors->classroom->has('title'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->classroom->first('title') }}</p>
                  </span>
              @endif
              </fieldset>
            </div>
            <div class="col-12">
              <fieldset class="form-group @if($errors->classroom->has('course_id')) danger @endif">
                <label for="user-name">Course *</label>
                <select class="select2 form-control" name="course_id" @if($data->id != '')disabled @endif>
                  <option value=""> กรุณาเลือก Course</option>
                  <optgroup label="Course">
                    @foreach ($course as $item )
                      <option value={{ $item }} 
                        @if(((string)$data->course_id == (string)$item)) selected  @endif
                      >{{ CourseClass::get_name_course($item) }}</option>
                    @endforeach
                  </optgroup>
                </select>
                @if($errors->classroom->has('course_id'))
                    <span class="small" role="alert">
                    <p class="mb-0">{{ $errors->classroom->first('course_id') }}</p>
                    </span>
                @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->classroom->has('company_id')) danger @endif">
              <label for="user-name">Company *</label>
              <select class="select2 form-control" id="div_content" name="company_id" onchange="handleCompany(this.value)">
                <option value=""> กรุณาเลือก Company</option>
                <optgroup label="Company">
                  @foreach ($company as $item )
                    <option value={{ $item }} 
                      @if( !empty($data->company_id)  && ((string)$data->company_id == (string)$item)) selected  @endif >{{ FuncClass::get_name_company($item) }} 
                    </option>
                  @endforeach
                </optgroup>
              </select>
              @if($errors->classroom->has('company_id'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->classroom->first('company_id') }}</p>
                  </span>
              @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->classroom->has('title')) danger @endif">
                <label for="user-name">Department *</label>
                <select class="select2 form-control" name="department_ids[]" multiple="multiple">
                </select>
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->classroom->has('published_at')) danger @endif">
                <label for="user-name">Published At *</label>
                <div class='input-group date published_at'  id='datetimepicker'>
                  <input type='text' class="form-control" name="published_at" @if(!empty($data->published_at)) value="{{old('published_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->published_at))}}" @else  value="{{old('published_at')}}"" @endif/> 
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <span class="fa fa-calendar"></span>
                    </span>
                  </div>
                </div>
                @if($errors->classroom->has('published_at'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->classroom->first('published_at') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->classroom->has('expired_at')) danger @endif">
                <label for="user-name">Expired At *</label>
                <div class='input-group date expired_at'  id='datetimepicker'>
                  <input type='text' class="form-control" name="expired_at" @if(!empty($data->expired_at)) value="{{old('expired_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->expired_at))}}" @else  value="{{old('expired_at')}}"" @endif/> 
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <span class="fa fa-calendar"></span>
                    </span>
                  </div>
                </div>
                @if($errors->classroom->has('expired_at'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->classroom->first('expired_at') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-12">
              @can('editor')
                <button type="submit" class="btn btn-primary btn-block">@if(!empty($data->id)) UPDATE @else SAVE @endif</button>
              @else
                <button  type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>CANCLE</button>
              @endcan
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
    {{-- <!-- Include Quill stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/quill.snow.css')}}"> --}}
    {{-- Date Time --}}
    <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
@endsection

@section('script')
    <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
    {{-- <!-- Include the Quill library -->
    <script src="{{ asset('js/quill.js')}}" type="text/javascript"></script> --}}
    {{-- Include Time Date --}}
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
    <script>
      $('.published_at').datetimepicker({
        format: 'DD-MM-YYYY',
      })
      $('.expired_at').datetimepicker({
        format: 'DD-MM-YYYY',
      })
      $( document ).ready(function() {
        var company_id = "{{ $data->company_id }}"
        var department_ids = JSON.parse(`{!! json_encode($data->department_ids) !!}`)
          handleCompany(company_id,department_ids)
          $('select[name="company_id"]').change(function () {
            var department_ids = [];
            var company_id = $(this).val()
            handleCompany(company_id,department_ids)
           })
      })
    
      function handleCompany(company_id,department_ids){
        if(company_id){
          var url = "{{ route('get_department_by_company') }}/"+company_id
          
          $.get(url, function (data) {
            // console.log(data)
            $("[name='department_ids[]']").empty();

            if(department_ids.count>0) {
              console.log('if')
              data.forEach(function (ch) {
                department_ids.map((departnmentId,index) => {
                  if (departnmentId.$oid === ch.id) {
                    $("[name='department_ids[]']").append(
                      $('<option> ', {
                        value: ch.id,
                        text: ch.topic,
                        selected: true
                      })
                    );
                  } else {
                    $("[name='department_ids[]']").append(
                      $('<option> ', {
                        value: ch.id,
                        text: ch.topic
                      })
                    );
                  }
                })
              })
            } else {
              console.log('else')
              data.forEach(function (ch) {
                $("[name='department_ids[]']").append(
                  $('<option> ', {
                    value: ch.id,
                    text: ch.topic
                  })
                )
              })
            }
            $("[name='department_ids[]']").attr('disabled', false)
          })
        } else {
          $("[name='department_ids[]']").find('option').remove()
          $("[name='department_ids[]']").append($('<option>', {
            value: '',
            text: 'Choose'
          }));
        } 
    }
</script>
@endsection
