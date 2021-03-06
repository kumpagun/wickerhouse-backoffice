@extends('layouts.app')

@php $title = strtoupper('product'); @endphp

@section('content-header-left')
  <h3 class="content-header-title mb-2">{{ $title }}</h3>
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
      <a class="btn btn-secondary" href="{{ route('product_create') }}">ADD {{ $title }}</a>
    @else 
      <button class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>ADD {{ $title }}</button>
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
      @foreach ($datas as $item)
      <div class="col-6 col-md-6 col-lg-4 col-xl-3">
        <a href="{{ route('product_create', ['id' => $item->_id]) }}">
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
