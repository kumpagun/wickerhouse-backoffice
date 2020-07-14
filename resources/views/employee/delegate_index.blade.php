@extends('layouts.app')

@php $title = 'ตัวแทนผู้บริหาร'; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
{{-- <div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div> --}}
@endsection

@section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
  <a class="btn btn-secondary" href="#" data-toggle="modal" data-target="#modalDelegate">เพิ่มตัวแทน</a>
  @else
  <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>เพิ่มตัวแทน</button>
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    @if (session('status'))
    <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <strong>Success</strong> บันทึกเรียบร้อยแล้ว
    </div>
    @endif
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <tr>
            <th class="text-center">No.</th>
            <th class="text-center">รหัสผู้บริหาร</th>
            <th class="text-center">ชื่อ - นามสกุล</th>
            <th class="text-center">ตำแหน่ง</th>
            <th class="text-center">ภูมิภาค</th>
            <th class="text-center">รหัสตัวแทน</th>
            <th class="text-center">ชื่อ - นามสกุล</th>
            <th class="text-center">ตำแหน่ง</th>
            <th class="text-center">Action</th>
          </tr>
          @if(!empty($datas) && count($datas)>0)
            @foreach ($datas as $item)
              @php 
                $executive = Member::get_employee_from_employee_id($item->employee_executive_id);
                $delegate = Member::get_employee_from_employee_id($item->employee_delegate_id);
              @endphp
              <tr>
                <td class="align-baseline text-center">{{ $loop->iteration }}</td>
                <td class="align-baseline text-center">{{ $item->employee_executive_id }}</td>
                <td class="align-baseline text-center">@if(!empty($executive)) {{ $executive->tf_name.' '.$executive->tl_name }} @else ไม่มีข้อมูล @endif</td>
                <td class="align-baseline text-center">@if(!empty($executive)) {{ $executive->title_name }} @else ไม่มีข้อมูล @endif</td>
                <td class="align-baseline text-center">@if(!empty($executive)) {{ $executive->region }} @else ไม่มีข้อมูล @endif</td>
                <td class="align-baseline text-center">{{ $item->employee_delegate_id }}</td>
                <td class="align-baseline text-center">@if(!empty($delegate)) {{ $delegate->tf_name.' '.$delegate->tl_name }} @else ไม่มีข้อมูล @endif</td>
                <td class="align-baseline text-center">@if(!empty($delegate)) {{ $delegate->title_name }} @else ไม่มีข้อมูล @endif</td>
                <td class="align-baseline text-center">
                  @can('editor')
                    @if(!$item->active)
                      <a href="#{{$item->_id}}" onclick="handleDelete('{{$item->_id}}')">
                        <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                      </a>
                    @endif
                  @else
                    <a><button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button></a>
                  @endcan
                </td>
              </tr>
            @endforeach
          @else
          <tr><td colspan="99" class="text-center">ไม่มีข้อมูลตัวแทน</td></tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modalDelegate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">เพิ่มตัวแทน</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('employee_delegate_store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <fieldset class="form-group">
          <label for="user-name"> รหัสพนักงานผู้บริหาร <span class="text-danger">*</span></label>
          <input class="form-control" name="employee_executive_id" id="employee_executive_id" type="text" required>
        </fieldset>
        <fieldset class="form-group">
          <label for="user-name"> รหัสพันกงานตัวแทน <span class="text-danger">*</span></label>
          <input class="form-control" name="employee_delegate_id" id="employee_delegate_id" type="text" required>
        </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปิด</button>
        <button type="submit" class="btn btn-outline-primary">บันทึก</button>
      </div>
    </form>
  </div>
  </div>
</div>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
{{-- Date Time --}}
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
{{-- Date Time --}}
@endsection

@section('script')
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
{{-- Date Time --}}
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
{{-- Date Time --}}
<script>
  $('.published_at').datetimepicker({
    format: 'DD-MM-YYYY'
  })
  $('.expired_at').datetimepicker({
    format: 'DD-MM-YYYY',
  })
  function handleDelete(id) {
    url = "{{ route('employee_delegate_delete') }}/"+id
    swal.fire({
      title: "คุณต้องการลบตัวแทนใช่หรือไม่ ?",
      icon: "warning",
      showCancelButton: true,
      buttons: {
        cancel: {
          text: "ยกเลิก",
          value: null,
          visible: true,
          className: "",
          closeModal: true,
        },
        confirm: {
          text: "ลบ",
          value: true,
          visible: true,
          className: "",
          closeModal: false
        }
      }
    }).then(isConfirm => {
      if (isConfirm.value) {
        window.location = url
      } 
    });
  }
</script>
@endsection