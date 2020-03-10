@extends('layouts.app')

@php $title = strtoupper(' สมาชิก VIP'); @endphp

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
      <a data-toggle="modal" data-target="#addEmployeeVIP"> <button class="btn btn-secondary"> เพิ่ม{{ $title }}</button></a>
    @else 
      <button class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>เพิ่ม{{ $title }}</button>
    @endcan
  </div>
@endsection

@section('content')
  <div class="row align-items-center justify-content-center mb-2">
    <div class="col-12 col-md-10 col-xl-8">
      @if (session('status'))
      <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <strong>Success</strong> บันทึกเรียบร้อยแล้ว
      </div>
      @endif
    </div>
    <div class="col-12 col-md-10 col-xl-8">
      <div class="card m-0">
        <div class="card-header">
          <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Employee ID</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @if(!empty($datas) && count($datas) > 0)
                @foreach ($datas as $item)
                  <tr>
                    <td class="align-middle"><a href="{{ route('roles_create', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
                    <td class="align-middle"><a href="{{ route('roles_create', ['id' => $item->id]) }}">{{ $item->tf_name.' '.$item->tl_name }}</a></td>
                    <td class="align-middle"><a href="{{ route('roles_create', ['id' => $item->id]) }}">{{ $item->employee_id }}</a></td>
                    <td class="align-middle">
                      @can('editor')
                        <button class="btn btn-danger" 
                          onclick="handleClickDel('{{$item->employee_id}}','{{ $item->tf_name.' '.$item->tl_name }}')">
                          ลบ
                        </button>
                      @else
                        <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>
                          ลบ
                        </button>
                      @endcan
                    </td>
                  </tr>
                @endforeach
              @else
                <tr><td colspan="99">ไม่มีข้อมูล สมาชิก VIP</td></tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Add Group --}}
  <div class="modal fade text-left" id="addEmployeeVIP" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel1">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form class="form-horizontal" action="{{ route('employee_vip_store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <fieldset class="form-group">
            <label for="user-name">ชื่อ - นามสกุล (Employee ID)</label>
            <select class="select2 form-control" name="employee_id" required>
              <option value=""> กรุณาเลือกสมาชิก</option>
              @foreach ($employees as $list )
                <option value='{{ $list->employee_id }}'>{{ $list->tf_name.' '.$list->tl_name.' ('.$list->employee_id.')' }}</option>
              @endforeach
            </select>
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

@section('script')
<script>
  function handleClickDel(employee_id, employee_name) {
    url = "{{ route('employee_vip_delete') }}"
    postData = {
      _token: "{{ csrf_token() }}",
      employee_id: employee_id
    }
    swal.fire({
      title: "คุณต้องการลบ " +employee_name+ " ใช่หรือไม่ ?",
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
        $.post(url, postData, function(data, status){
          location.reload()
        });
      }
    });
  }
</script>
@endsection
