@extends('layouts.app')

@php $title = strtoupper('Course List'); @endphp

@section('content-header-left')
  <h3 class="content-header-title mb-2">{{ $title }}</h3>
  <div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Course</li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
  </div>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right mb-2" role="group" aria-label="Button group with nested dropdown">
    @can('editor')
      <a class="btn btn-secondary" href="{{ route('course_create') }}">Add Course</a>
    @endcan
  </div>
@endsection

@section('content')
  <div class="row match-height">
    @foreach ($datas as $item)
    <div class="col-6 col-md-6 col-lg-4 col-xl-3">
      <a href="{{ route('course_create', ['id' => $item->_id]) }}">
      <div class="card">
        <div class="card-content">
          @if($item->test_status==1) 
          <img class="card-img img-fluid" src="{{ env('IMG_PATH_TUTORME').$item->thumbnail }}" alt="Card image cap">
          @else
          <img class="card-img img-fluid" src="{{ env('IMG_PATH').$item->thumbnail }}" alt="Card image cap">
          @endif
          <div class="card-body">
            <h4 class="card-title">{{ $item->title }}</h4>
            <p class="card-text text-right">
              @if($item->status==1)
                <span class="text-success">Online</span>
              @else 
                <span class="text-danger">Offline</span>
              @endif
            </p>
          </div>
        </div>
      </div>
      </a>
    </div>
    @endforeach  
  </div>
@endsection

@section('style')
  <style>
    a {
      color: black;
    }
  </style>
@endsection

@section('script')

@endsection
