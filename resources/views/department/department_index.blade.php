@extends('layouts.app')

@php $title = strtoupper('List department'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item">Department</li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
    <a class="btn btn-secondary" href="{{ route('create_department') }}"><i class="ft-user"></i> Add Department</a>
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h4 class="card-title">Department list</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
                <tr>
                  <td class="text-center no-table">#</td>
                  <td class="text-center content-table">Department Name</td>
                  <td class="text-center content-table">Company Name</td>
                </tr>
                @if (!empty($datas))
                    @foreach ($datas as $item)
                        <tr>
                          <td class="text-center"><a href="{{ route('create_department', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
                          <td class="text-left"><a href="{{ route('create_department', ['id' => $item->id]) }}">{{ $item->title }}</a></td>
                          <td class="text-left"><a href="{{ route('create_department', ['id' => $item->id]) }}">{{ FuncClass::get_name_company($item->company_id) }}</a></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                      <td class="text-center" colspan="99">
                        {{"ไม่มีข้อมูล"}}
                      </td>
                    </tr>   
                @endif
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('style')
  <style>
    .no-table{
      width: 10%;
    }
    .content-table{
      width: 40%;
    }
  </style>
@endsection