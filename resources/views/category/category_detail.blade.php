@extends('layouts.app')

@php $title = strtoupper('เพิ่มประเภทของหลักสูตร'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('category_index') }}">ประเภทของหลักสูตร</a></li>
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
          <span>รายละเอียดประเภทของหลักสูตร</span>
        </h6>
      </div>
      <div class="card-content ">
        <div class="card-body py-0 ">
          <form class="form-group row" action="{{ route('category_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="col-6">
              <fieldset class="form-group @if($errors->category->has('title')) danger @endif">
              <label for="user-name">Title *</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="category Name">
              @if($errors->category->has('title'))
                <span class="small" role="alert">
                <p class="mb-0">{{ $errors->category->first('title') }}</p>
                </span>
              @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->category->has('code')) danger @endif">
                <label for="user-name">Code *</label>
                <input id="input-code" type="text" name="code" class="form-control" maxlength="3" value="{{ old('code', $data->code) }}" placeholder="Code">
                <span><small>* ตัวอักษร 3 ตัว</small></span>
                @if($errors->category->has('code'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->category->first('code') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->category->has('slug')) danger @endif">
                <label for="user-name">Slug *</label>
                <input id="input-slug" type="text" name="slug" class="form-control" value="{{ old('slug', $data->slug) }}" placeholder="slug">
                @if($errors->category->has('slug'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->category->first('slug') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-12">
              <fieldset class="form-group @if($errors->category->has('description')) danger @endif">
                <label for="content">Description</label>
                <textarea name="description" id="description" class="form-control" cols="30" rows="10">{!! $data->description !!}</textarea>
                @if($errors->category->has('description'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->category->first('description') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-12">
              @can('editor')
                <button type="submit" class="btn btn-primary btn-block">บันทึก</button>
              @else
                <button type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>NOT ALLOW</button>
              @endcan
            </div>
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
    $("#input-slug").keyup(function(e) {
    var regex = /^[a-zA-Z@]+$/;
    if (regex.test(this.value) !== true)
        this.value = this.value.replace(/[^a-zA-Z@]+/, '');
    });
    $("#input-code").keyup(function(e) {
        var regex = /^[a-zA-Z@]+$/;
        if (regex.test(this.value) !== true)
          this.value = this.value.replace(/[^a-zA-Z@]+/, '');
    });
  </script>
@endsection
