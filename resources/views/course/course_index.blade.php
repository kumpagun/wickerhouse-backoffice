@extends('layouts.app')

@php $title = strtoupper('หลักสูตรทั้งหมด'); @endphp

@section('content-header-left')
  <h3 class="content-header-title mb-2">{{ $title }}</h3>
  <div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">หลักสูตรการเรียน</li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
  </div>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right mb-2" role="group" aria-label="Button group with nested dropdown">
    <form action="">
      <div class="input-group px-1">
        <input type="text" class="form-control" name="search" aria-describedby="basic-addon2" placeholder="ชื่อคอร์ส" value="{{ $search }}">
        <div class="input-group-append">
          <button type="submit" class="input-group-text" id="basic-addon2">ค้นหา</button>
        </div>
      </div>
    </form>
    @can('editor')
      <a class="btn btn-secondary" href="{{ route('course_create') }}">เพิ่มหลักสูตร</a>
    @else 
      <button class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>เพิ่มหลักสูตร</button>
    @endcan
  </div>
@endsection

@section('content')
  <div class="row match-height">
    @if(!empty($datas) && count($datas) > 0)
      @foreach ($datas as $item)
      <div class="col-6 col-md-6 col-lg-4 col-xl-3">
        <a href="{{ route('course_create', ['id' => $item->_id]) }}">
        <div class="card o-hidden">
          <div class="card-content">
            <img class="card-img img-fluid" src="{{ config('app.url').'storage/'.$item->thumbnail }}" alt="Card image cap">
            <div class="card-body">
              <h4 class="card-title">{{ $item->title }}</h4>
              <p class="card-text text-right">
                @if($item->type=='standard') 
                <span>ประเภทหลักสูตรมาตรฐาน</span>
                @else
                <span>ประเภทหลักสูตรทั่วไป</span>
                @endif

                @if($item->status==1)
                  <span class="status text-success">Online</span>
                @else 
                  <span class="status text-danger">Offline</span>
                @endif
              </p>
            </div>
          </div>
        </div>
        </a>
      </div>
      @endforeach  
    @else
      <div class="col-12">ไม่มีข้อมูล</div>
    @endif
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
