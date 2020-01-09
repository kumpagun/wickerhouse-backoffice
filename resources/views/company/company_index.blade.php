@extends('layouts.app')

@php $title = strtoupper('List Company'); @endphp

@section('content-header-left')
  <h3 class="content-header-title mb-2">{{ $title }}</h3>
  <div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Company</li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div>
  </div>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
    @can('editor')
      <a class="btn btn-secondary" href="{{ route('company_create') }}"><i class="ft-user"></i> Add Company</a>
    @endcan
  </div>
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Company list</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-hover ">
            <tr>
              <th class="text-center no-table">#</th>
              <th class="text-center content-table">Company Name</th>
            </tr>
            @if (!empty($datas))
              @foreach ($datas as $item)
                <tr> 
                  <td class="text-center"><a href="{{ route('company_create', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
                  <td class="text-left"><a href="{{ route('company_create', ['id' => $item->id]) }}">{{ $item->title }}</a></td>
                </tr>
              @endforeach
            @else
              <tr>
                <td class="text-center" colspan="99">ไม่มีข้อมูล</td>
              </tr>   
            @endif
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('style')
  <style>
    .no-table{
      width: 5%;
    }
    .content-table{
      width: 95%;
    }
  </style>
@endsection