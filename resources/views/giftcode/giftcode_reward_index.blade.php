@extends('layouts.app')

@php $title = $training->title; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('giftcode_group_index') }}">กิจกรรมแจกของรางวัล</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
  <a class="btn btn-secondary" href="#" data-toggle="modal" data-target="#modalGiftcode">เพิ่มของรางวัล</a>
  @else
  <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>เพิ่มของรางวัล</button>
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
            <th class="text-center">#</th>
            <th class="text-center">ของรางวัล</th>
            <th class="text-center">รหัส</th>
            <th class="text-center">สถานะ</th>
            <th class="text-center">ผู้ได้รับรางวัล</th>
            <th class="text-center">Action</th>
          </tr>
          @if(!empty($datas) && count($datas)>0)
            @foreach ($datas as $item)
              <tr>
                <td class="align-baseline text-center">{{ $loop->iteration }}</td>
                <td class="align-baseline">{{ $item->title }}</td>
                <td class="align-baseline text-center">{{ $item->code }}</td>
                <td class="align-baseline text-center">@if($item->active) <span class="text-success">ถูกใช้งานแล้ว</span> @else ยังไม่ถูกใช้งาน @endif</td>
                <td class="align-baseline text-center">{{ Member::getUserFromGiftcode($item->_id) }}</td>
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
          <tr><td colspan="99" class="text-center">ไม่มีของรางวัล</td></tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modalGiftcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">เพิ่มของรางวัล</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('giftcode_reward_store') }}" method="POST">
      @csrf
      <input type="hidden" name="giftcode_group_id" value="{{ $giftcode_group_id }}">
      <div class="modal-body">
        <fieldset class="form-group">
          <label for="user-name"> ชื่อรางวัล <span class="text-danger">*</span></label>
          <input class="form-control" name="title" id="title" type="text" required>
        </fieldset>
        <fieldset class="form-group">
          <label for="user-name"> จำนวน <span class="text-danger">*</span></label>
          <input class="form-control" name="total" id="total" type="number" required>
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
    url = "{{ route('giftcode_reward_delete') }}/"+id
    swal.fire({
      title: "คุณต้องการลบรางวัลใช่หรือไม่ ?",
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