@extends('layouts.app')

@php $title = strtoupper('ประวัติการส่งอีเมล์'); @endphp

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

{{-- @section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
    <a class="btn btn-secondary" href="{{ route('create_department') }}"><i class="ft-user"></i> เพิ่มแผนก</a>
  @endcan
</div>
@endsection --}}

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
          <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <tr>
              <th class="text-center no-table">#</th>
              <th class="text-center content-table">รอบการอบรม</th>
              <th class="text-center content-table">ประเภท</th>
              <th class="text-center content-table">จำนวน</th>
            </tr>
            @if (!empty($mail_log) && count($mail_log) > 0)
              @foreach ($mail_log as $item)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td class="text-center"><a href="{{ route('email_log_detail',['mail_log_id' => $item->_id]) }}">{{ CourseClass::get_training_name($item->training_id) }}</a></td>
                  <td class="text-center">
                    <a href="{{ route('email_log_detail',['mail_log_id' => $item->_id]) }}">
                    @if($item->type=='new_training')
                      รอบการอบรมใหม่
                    @elseif($item->type=='alert_training_not_complete') 
                      แจ้งเตือนผู้ที่ยังไม่ผ่านการอบรม
                    @else
                      {{ $item->type }}
                    @endif
                    </a>
                  </td>
                  <td class="text-center"><a href="{{ route('email_log_detail',['mail_log_id' => $item->_id]) }}">{{ count($item->employee_id) }}</a></td>
                </tr>
              @endforeach
            @else
              <tr>
                <td class="text-center" colspan="99">
                  ไม่มีข้อมูล
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