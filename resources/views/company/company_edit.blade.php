@extends('layouts.app')

@php $title = strtoupper('เพิ่มบริษัท'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('company_index') }}">บริษัท</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-md-6 col-10">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
          <span>รายละเอียดบริษัท</span>
        </h6>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form class="form-horizontal" action="{{ route('company_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <fieldset class="form-group floating-label-form-group @if($errors->company->has('title')) danger @endif">
              <label for="user-name">ชื่อบริษัท</label>
              <input type="text" name="title" class="form-control" id="user-name" value="{{ old('title', $data->title) }}" placeholder="ชื่อบริษัท">
              @if($errors->company->has('title'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->company->first('title') }}</p>
                </span>
              @endif
            </fieldset>
            @can('editor')
            <button type="submit" class="btn btn-primary btn-block"> บันทึก </button>
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
@endsection
