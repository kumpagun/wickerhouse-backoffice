@extends('layouts.app')

@php $title = strtoupper('giftcode'); @endphp

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
  <a class="btn btn-secondary" href="#" data-toggle="modal" data-target="#modalGiftcode">Add {{ $title }}</a>
  @else
  <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>Add {{ $title }}</button>
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
            <th class="text-center">รอบการอบรม</th>
            <th class="text-center">รายละเอียด</th>
            <th class="text-center">ของรางวัล</th>
            <th class="text-center">Status</th>
          </tr>
          @foreach ($datas as $item)
            <tr>
              <td class="align-baseline text-center"><a href="{{ route('giftcode_group_create', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
              <td class="align-baseline"><a href="{{ route('giftcode_group_create', ['id' => $item->id]) }}">{{ CourseClass::get_training_name($item->training_id) }}</a></td>
              <td class="align-baseline"><a href="{{ route('giftcode_group_create', ['id' => $item->id]) }}">{{ $item->description }}</a></td>
              <td width="170" class="align-baseline text-center">
                <a href="{{ route('giftcode_reward_index', ['giftcode_group_id' => $item->_id]) }}">
                  <button class="btn btn-secondary"><i class="ft-close"></i> ดูของรางวัล</button>
                </a>
              </td>
              <td class="align-baseline text-center">
                @can('editor')
                  <a href="#{{$item->_id}}" onclick="handleDelete('{{$item->_id}}')">
                    <button class="btn btn-danger"><i class="ft-close"></i> ลบ</button>
                  </a>
                @else
                  <a><button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-close"></i> ลบ</button></a>
                @endcan
              </td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade text-left" id="modalGiftcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel1">Add {{ $title }}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <form class="form-horizontal" action="{{ route('giftcode_group_store') }}" method="POST">
      @csrf
      <div class="modal-body">
        <fieldset class="form-group">
          <label for="user-name"> รอบการอบรม <span class="text-danger">*</span> </label>
          <select class="select2 form-control" name="training_id" required>
            @foreach ($training as $item)
              <option value="{{ $item->_id }}">{{ $item->title }}</option>
            @endforeach
          </select>
        </fieldset>
        <fieldset class="form-group">
          <label for="user-name"> รายละเอียด </label>
          <textarea class="form-control" name="description" id="description" cols="30" rows="10"></textarea>
        </fieldset>
        <fieldset class="form-group">
          <label for="user-name">วันที่เริ่มใช้งาน <span class="text-danger">*</span></label>
          <div class='input-group date published_at'  id='datetimepicker'>
            <input type='text' class="form-control" name="published_at" @if(!empty($data->published_at)) value="{{old('published_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->published_at))}}" @else  value="{{old('published_at')}}" @endif required/> 
            <div class="input-group-append">
              <span class="input-group-text">
                <span class="fa fa-calendar"></span>
              </span>
            </div>
          </div>
        </fieldset>
        <fieldset class="form-group">
          <label for="user-name">วันที่สิ้นสุดการใช้งาน <span class="text-danger">*</span></label>
          <div class='input-group date expired_at'  id='datetimepicker'>
            <input type='text' class="form-control" name="expired_at" @if(!empty($data->expired_at)) value="{{old('expired_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->expired_at))}}" @else  value="{{old('expired_at')}}" @endif required/> 
            <div class="input-group-append">
              <span class="input-group-text">
                <span class="fa fa-calendar"></span>
              </span>
            </div>
          </div>
        </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">ปืด</button>
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
    url = "{{ route('giftcode_group_delete') }}/"+id
    swal.fire({
      title: "คุณต้องการลบคำถามใช่หรือไม่ ?",
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