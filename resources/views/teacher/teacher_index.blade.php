@extends('layouts.app')

@php $title = strtoupper('Teacher List'); @endphp

@section('content-header-left')
  <h3 class="content-header-title mb-2">{{ $title }}</h3>
  <div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Teacher</li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
  </div>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right mb-2" role="group" aria-label="Button group with nested dropdown">
    @can('editor')
      <a class="btn btn-secondary" href="{{ route('teacher_create') }}">Add Teacher</a>
    @endcan
  </div>
@endsection

@section('content')
  @if (session('status'))
  <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    <strong>Success</strong> บันทึกเรียบร้อยแล้ว
  </div>
  @endif
  <div class="row match-height">
    @if(!empty($datas) && count($datas) > 0)
      @foreach ($datas as $data)
      <div class="col-xl-3 col-md-6 col-sm-12">
        <div class="card o-hidden">
          <div class="card-content">
            <div class="card-body">
              <h4 class="card-title">{{ $data->name }}</h4>
              <h6 class="card-subtitle text-muted">{{ $data->label }}</h6>
            </div>
            @if($data->test_status==1) 
            <img class="img-fluid" src="{{ env('IMG_PATH_TUTORME').$data->profile_image }}" alt="Card image cap">
            @else 
              <img class="img-fluid" src="{{ env('IMG_PATH').$data->profile_image }}" alt="Card image cap">
            @endif
            <div class="card-body">
              <p class="card-text">{{ $data->subtitle }}</p>
              <p class="text-right mb-0">
                <a href="{{ route('teacher_create', ['id' => $data->_id]) }}" class="card-link pink">Edit</a>
              </p>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    @else
      <div class="col-12">ไม่มีข้อมูล</div>
    @endif
  </div>
@endsection

@section('script')

@endsection
