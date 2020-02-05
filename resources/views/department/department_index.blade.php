@extends('layouts.app')

@php $title = strtoupper('แผนก'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
    <a class="btn btn-secondary" href="{{ route('create_department') }}"><i class="ft-user"></i> เพิ่มแผนก</a>
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
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">แผนก</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <tr>
              <th class="text-center no-table">#</th>
              <th class="text-center content-table">Department Name</th>
              <th class="text-center content-table">Company Name</th>
            </tr>
            @if (!empty($datas) && count($datas) > 0)
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