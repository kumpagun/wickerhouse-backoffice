@extends('layouts.app')

@php $title = strtoupper('รายชื่อผู้เข้าอบรมทั้งหมด'); @endphp

@section('content-header-right')
  <div class="btn-group float-md-right mb-2" role="group" aria-label="Button group with nested dropdown">
    <form action="">
      <div class="input-group">
        <input type="text" class="form-control" name="search" aria-describedby="basic-addon2" placeholder="employee id" value="{{ $search }}">
        <div class="input-group-append">
          <button type="submit" class="input-group-text" id="basic-addon2">ค้นหา</button>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('content-header-left')
    <h3 class="content-header-title mb-2">{{ $title }}</h3>
    <div class="row breadcrumbs-top">
    <div class="breadcrumb-wrapper col-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('training_index') }}">รอบอบรมทั้งหมด</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div>
    </div>
@endsection
@section('content')
    @if(Session::has('msg'))
        <div class="alert alert-danger mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>!!!</strong> {{Session::get('msg')}}.
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                  <h4 class="card-title">รายชื่อผู้เข้าอบรมทั้งหมด</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">EmployeeId</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                            @if (count($datas))
                              @foreach ($datas as $data)
                                @if(!empty($data->employee_id))
                                <tr id="{{ $data->employee_id }}">
                                  <td class="align-middle text-center">{{$loop->iteration}} </td>
                                  <td class="align-middle text-left">{{ $data->employee_id }} </td>
                                  <td class="align-middle text-left">{{ Member::get_name_member_jasmine($data->employee_id) }} </td>
                                  <td class="align-middle text-center">{{ FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->created_at,'d-m-Y H:i')}}</td>
                                  <td class="text-center">
                                    @can('editor')
                                      <button class="btn btn-danger" 
                                        onclick="handleClickDel('{{$training_id}}','{{$data->employee_id}}','{{Member::get_name_member_jasmine($data->employee_id)}}')">
                                        ลบ
                                      </button>
                                    @else
                                      <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>
                                        ลบ
                                      </button>
                                    @endcan
                                  </td>
                                </tr>
                                @else 
                                <tr id="{{ $data->_id['employee_id'] }}">
                                  <td class="align-middle text-center">{{$loop->iteration}} </td>
                                  <td class="align-middle text-left">{{ $data->_id['employee_id'] }} </td>
                                  <td class="align-middle text-left">{{ Member::get_name_member_jasmine($data->_id['employee_id']) }} </td>
                                  {{-- <td class="align-middle text-center">{{ FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->_id['created_at'],'d-m-Y H:i')}}</td> --}}
                                  <td class="text-center">
                                    @can('editor')
                                      <button class="btn btn-danger" 
                                        onclick="handleClickDel('{{$training_id}}','{{$data->_id['employee_id']}}','{{Member::get_name_member_jasmine($data->_id['employee_id'])}}')">
                                        ลบ
                                      </button>
                                    @else
                                      <button class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>
                                        ลบ
                                      </button>
                                    @endcan
                                  </td>
                                </tr>
                                @endif
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
                    <div class="text-right">
                      {{ $datas->appends(['search' => $search])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
  <script>
    function handleClickDel(training_id, employee_id, employee_name) {
      url = "{{ route('training_user_delete') }}"
      postData = {
        _token: "{{ csrf_token() }}",
        training_id: training_id,
        employee_id: employee_id
      }
      swal({
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
        if (isConfirm) {
          $.post(url, postData, function(data, status){
            console.log(data, status)
            $('#'+employee_id).empty()
            swal('Complete')
          });
        } else {

        }
      });
    }
  </script>
@endsection
