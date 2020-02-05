@extends('layouts.app')

@php $title = $training->title @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('homework_index') }}">ตรวจแบบฝึกหัดหลังเรียน</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
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
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ $title }}</h4>
          <div class="heading-elements">
            <ul class="list-inline mb-0">
              <li>
                <a href="{{ route('homework_answer_index', ['training_id' => $training->_id]) }}">
                  <button class="badge badge @if(empty($type)) badge-primary @else badge-secondary @endif badge-pill">ทั้งหมด</button>
                </a>
              </li>
              <li>
                <a href="{{ route('homework_answer_index', ['training_id' => $training->_id, 'type' => 'no_answer']) }}">
                  <button class="badge badge @if(!empty($type) && $type=='no_answer') badge-primary @else badge-secondary @endif badge-pill">ยังไม่ได้ตรวจ</button>
                </a>
              </li>
              <li>
                <a href="{{ route('homework_answer_index', ['training_id' => $training->_id, 'type' => 'answer']) }}">
                  <button class="badge badge @if(!empty($type) && $type=='answer') badge-primary @else badge-secondary @endif badge-pill">ตรวจแล้ว</button>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <tr>
              <th class="text-center table-no">#</th>
              <th class="text-center">รหัสพนักงาน</th>
              <th class="text-center">ชื่อพนักงาน</th>
              <th class="text-center">วันที่ตอบ</th>
              <th class="text-center">ผล</th>
            </tr>
            @if(count($datas)>0)
            @foreach ($datas as $item)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">  
                  <a href="#" data-toggle="modal" data-target="#answer-{{$item->_id}}">{{ Member::get_employeeId_member_jasmine_by_id($item->user_id) }}</a>
                </td>
                <td>  
                  <a href="#" data-toggle="modal" data-target="#answer-{{$item->_id}}">{{ Member::get_name_member_jasmine_by_id($item->user_id) }}</a>
                </td>
                <td class="text-center">{{ FuncClass::utc_to_carbon_format_time_zone_bkk($item->created_at) }}</td>
                @if($item->result=='fail')
                <td class="text-center text-danger">ไม่ผ่าน</td>
                @elseif($item->result=='pass')
                <td class="text-center text-success">ผ่าน</td>
                @else
                <td class="text-center text-warning">ยังไม่ได้ตรวจ</td>
                @endif
              </tr>
            @endforeach
            @else
              <tr><td colspan="99" class="text-center">ไม่มีข้อมูล</td></tr>
            @endif
          </table>
        </div>
        <div class="card-footer border-0 text-center">
          {{ $datas->links() }}
        </div>
      </div>
    </div>
  </div>

  @foreach ($datas as $item)
    <div class="modal fade text-left" id="answer-{{$item->_id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel1">{{ Member::get_name_member_jasmine_by_id($item->user_id) }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form class="form-horizontal" action="{{ route('homework_answer_store') }}" method="POST">
            @csrf
            <input type="hidden" name="homework_answer_id" value="{{ $item->_id }}" />
            <div class="modal-body">
              <div class="row mb-2">
                <div class="col-12"><strong>คำถาม</strong></div>
                <div class="col-12 max-width">{!! $homework->question !!}</div>
              </div>
              <hr>
              <div class="row mb-2">
                <div class="col-12"><strong>คำตอบ</strong></div>
                <div class="col-12">{!! $item->answer_text !!}</div>
                <div class="col-12 mb-2"><img src="http://bo-dev.jasonlinelearning.com/storage/homeworks/5e31523f3d1420696e1a1ee3/1580719887002.png" alt=""></div>
                <div class="col-12"><strong>วันที่ตอบ </strong>{{ FuncClass::utc_to_carbon_format_time_zone_bkk($item->created_at) }}</div>
              </div>
              <hr>
              <div class="row skin skin-square mb-2">
                <div class="col-12 mb-2">
                  <strong for="user-status">รายละเอียดการตรวจ</strong>
                  <textarea name="description" class="form-control mt-1" rows="10" @if(!empty($item->inspect_at)) disabled @endif >{{ $item->description }}</textarea>
                </div>
                <div class="col-12 mb-2">
                  <strong for="user-status">ผลการตรวจ</strong>
                  <div class="mt-1">
                    <fieldset>
                      <input type="radio" name="result" id="input-radio-active-{{$item->_id}}" value="pass" @if(!empty($item->result) && $item->result=='pass') checked @endif required @if(!empty($item->inspect_at)) disabled @endif>
                      <label for="input-radio-active-{{$item->_id}}">ผ่าน</label>
                    </fieldset>
                    <fieldset>
                      <input type="radio" name="result" id="input-radio-inactive-{{$item->_id}}" value="fail" @if(!empty($item->result) && $item->result=='fail') checked @endif required @if(!empty($item->inspect_at)) disabled @endif>
                      <label for="input-radio-inactive-{{$item->_id}}">ไม่ผ่าน</label>
                    </fieldset>
                  </div>
                </div>
                @if(!empty($item->inspect_at))
                <div class="col-12 mb-2"><strong>วันที่ตรวจ </strong>{{ FuncClass::utc_to_carbon_format_time_zone_bkk($item->inspect_at) }}</div>
                @endif
              </div>
            </div>
            @if(empty($item->inspect_at)) 
              <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-outline-primary">บันทึก</button>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection

@section('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
  <style>
    img{max-width:100% !important;}
  </style>
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
@endsection