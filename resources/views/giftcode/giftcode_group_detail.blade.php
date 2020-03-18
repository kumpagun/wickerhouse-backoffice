@extends('layouts.app')

@php $title = $training->title; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('giftcode_group_index') }}">GIFTCODE</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-12 col-md-10 col-xl-8">
    @if (session('status'))
    <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <strong>Success</strong> บันทึกเรียบร้อยแล้ว
    </div>
    @endif
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form class="form-horizontal" action="{{ route('giftcode_group_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->_id }}">
            <input type="hidden" name="training_id" value="{{ $data->training_id }}">
            <fieldset class="form-group">
              <label for="user-name"> รายละเอียด </label>
              <textarea class="form-control" name="description" id="description" cols="30" rows="10">{{ $data->description }}</textarea>
            </fieldset>
            <fieldset class="form-group">
              <label for="user-name">วันที่เริ่มใช้งาน <span class="text-danger">*</span></label>
              <div class='input-group date published_at'  id='datetimepicker'>
                <input type='text' class="form-control" name="published_at" @if(!empty($data->published_at)) value="{{old('published_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->published_at))}}" @else  value="{{old('published_at')}}" @endif required/> 
                <div class="input-group-append">
                  <span class="input-group-text">
                    <span class="fa fa-calendar"></span>
                  </span>
                </div>
              </div>
            </fieldset>
            {{-- <fieldset class="form-group">
              <label for="user-name">วันที่สิ้นสุดการใช้งาน <span class="text-danger">*</span></label>
              <div class='input-group date expired_at'  id='datetimepicker'>
                <input type='text' class="form-control" name="expired_at" @if(!empty($data->expired_at)) value="{{old('expired_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->expired_at))}}" @else  value="{{old('expired_at')}}" @endif required/> 
                <div class="input-group-append">
                  <span class="input-group-text">
                    <span class="fa fa-calendar"></span>
                  </span>
                </div>
              </div>
            </fieldset> --}}
            @can('editor')
            <button type="submit" class="btn btn-primary btn-block">บันทึก</button>
            @else
            <button  type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>NOT ALLOW</button>
            @endcan
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
{{-- Date Time --}}
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
{{-- Date Time --}}
@endsection

@section('script')
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
{{-- Date Time --}}
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
{{-- Date Time --}}
<script>
  $('.published_at').datetimepicker({
    format: 'DD-MM-YYYY'
  })
  $('.expired_at').datetimepicker({
    format: 'DD-MM-YYYY',
  })
</script>
@endsection
