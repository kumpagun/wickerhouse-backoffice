@extends('layouts.app')

@php $title = strtoupper('add permission'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('permissions_index') }}">PERMISSIONS</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-12 col-md-10 col-xl-8">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        <div class="card-title text-center">
          <img src="{{ asset('stack-admin/app-assets/images/logo/stack-logo-dark.png') }}" alt="branding logo">
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
          <span>PERMISSIONS INFO</span>
        </h6>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form class="form-horizontal" action="{{ route('permissions_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('display_name')) danger @endif">
              <label for="user-name">Display Name</label>
              <input type="text" name="display_name" class="form-control" id="user-name" value="{{ old('display_name', $data->display_name) }}" placeholder="Display Name">
              @if($errors->register->has('display_name'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('display_name') }}</p>
                </span>
              @endif
            </fieldset>
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('name')) danger @endif">
              <label for="user-name">Name (ใช้สำหรับดักสิทธิ์การเข้าใช้งาน)</label>
              <input type="text" name="name" class="form-control" id="user-name" placeholder="Name"
                value="{{ old('name', $data->name) }}" 
                @if(!empty($data->id)) disabled @endif
              >
              @if($errors->register->has('name'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('name') }}</p>
                </span>
              @endif
            </fieldset>
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('description')) danger @endif">
              <label for="user-description">Description</label>
              <input type="text" name="description" class="form-control" id="user-description" placeholder="Description" value="{{ old('description', $data->description) }}" >
              @if($errors->register->has('description'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('description') }}</p>
                </span>
              @endif
            </fieldset>
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
@endsection

@section('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
  <script>
    var error = {{ $errors->resetpassword->has('password') }}
    if(error) $('#xSmall').modal()

    // var success = {{ session()->has('success') }}
    // if(success) alert('success')
  </script>
@endsection
