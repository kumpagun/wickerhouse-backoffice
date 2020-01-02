@extends('layouts.app')

@php $title = strtoupper('add episode'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $course_id, ['#episodelist']]) }}">Course</a></li>
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
          <span>COMPANY INFO</span>
        </h6>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          {{-- <form class="form-horizontal" action="{{ route('company_store') }}" method="POST"> --}}
            {{-- @csrf
            <input type="hidden" name="id" value="{{ $datas->id }}">
            <input type="hidden" name="course_id" value="{{ $datas->course_id }}">
             --}}
           
          {{-- </form> --}}
        </div>
    </div>
  </div>
</div>
@endsection

@section('style')
@endsection

@section('script')
@endsection
