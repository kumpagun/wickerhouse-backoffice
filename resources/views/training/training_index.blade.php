@extends('layouts.app')

@php $title = strtoupper('รอบอบรมทั้งหมด'); @endphp

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
    <form action="">
      <div class="input-group px-1">
        <input type="text" class="form-control" name="search" aria-describedby="basic-addon2" placeholder="ชื่อรอบอบรม" value="{{ $search }}">
        <div class="input-group-append">
          <button type="submit" class="input-group-text" id="basic-addon2">ค้นหา</button>
        </div>
      </div>
    </form>
    @can('editor')
      <a href="{{ route('training_create') }}">
        <button class="btn btn-secondary">เพิ่มรอบอบรม</button>
      </a>
    @else
      <a><button class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>เพิ่มรอบอบรม</button></a>
    @endcan
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
          <div class="card-header">
            <h4 class="card-title">รอบอบรมทั้งหมด</h4>
          </div>
          <div class="table-responsive">
            <table class="table table-hover">
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Title</th>
                <th class="text-center">Course</th>
                <th class="text-center">Status</th>
                <th class="text-center" width="10%">วันที่เริ่มต้น - สิ้นสุด</th>
                <th class="text-center">Import Excel</th>
                <th class="text-center">จำนวนผู้เข้าร่วม</th>
              </tr>
              @if (count($datas))
                @foreach ($datas as $data)
                  <tr>
                    @if (FuncClass::checkCurrentDate($data->published_at, $data->expired_at)=='จบแล้ว')
                      <td class="align-middle text-center">{{$loop->iteration}} </td>
                      <td class=" align-middle text-left">{{$data->title}} </td>
                      <td class="align-middle text-left">{{CourseClass::get_name_course((string)$data->course_id)}} </td>
                      <td class="align-middle text-center">{{ FuncClass::checkCurrentDate($data->published_at, $data->expired_at) }}</td>
                      <td class="align-middle text-center p-0">
                        {{FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->published_at,'d/m/Y')}} - {{FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->expired_at,'d/m/Y')}}
                      </td> 
                      <td class="align-middle text-center"> 
                        <button type="button" class="btn btn-outline-secondary btn-min-width mx-1" disabled>
                          Import File 
                        </button>
                      </td>
                    @else
                      <td class="align-middle text-center"><a href="{{ route('training_create', ['id' => $data->id]) }}">{{$loop->iteration}} </a></td>
                      <td class=" align-middle text-left"><a href="{{ route('training_create', ['id' => $data->id]) }}">{{$data->title}} </a></td>
                      <td class="align-middle text-left"><a href="{{ route('training_create', ['id' => $data->id]) }}">{{CourseClass::get_name_course((string)$data->course_id)}} </a></td>
                      <td class="align-middle text-center">{{ FuncClass::checkCurrentDate($data->published_at, $data->expired_at) }}</td>
                      <td class="align-middle text-center p-0">
                        {{FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->published_at,'d/m/Y')}} - {{FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->expired_at,'d/m/Y')}}
                      </td> 
                      <td class="align-middle text-center"> 
                        @can('editor')
                          <button type="button" class="btn btn-outline-secondary btn-min-width mx-1"  aria-hidden="true" aria-label="Close" data-toggle="modal" data-target="#AnswerModal{{$data->_id}}">
                            Import File 
                          </button>
                        @else
                          <button type="button" class="btn btn-outline-secondary btn-min-width mx-1"  data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>
                            Import File 
                          </button>
                        @endcan
                      </td>
                    @endif
                    <td class="align-middle text-center">
                      <a href="{{ route('traingin_user_list', ['id' => $data->id]) }}">
                        รายชื่อ <span class="badge badge-pill badge-primary">{{FuncClass::count_user_in_traingin($data->_id)}}</span>
                      </a>
                    </td>  
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
    @if(count($datas))
        @foreach ($datas as $data)
            <div class="modal-alert modal fade" id="AnswerModal{{$data->_id}}" tabindex="-1" role="dialog" aria-labelledby="AnswerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <form class="form" method="POST" action="{{ URL::route('import_excel') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-header">
                        <h3 class="modal-title" id="AnswerModalLabel"> กรุณาเลือก File Excel</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                            <input name="class_id" type="hidden" value="{{ $data->_id }}" />
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <div class="form-group">
                                    <label class="text">Excel</label>
                                    <input name="excel" class="form-control" type="file" >
                                </div>
                                @if($errors->first('excel'))<p><small class="danger text-muted">{{$errors->first('excel')}}</small></p>@endif
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <a href="{{ asset("Files/example.xlsx") }}">ตัวอย่างไฟล์ import</a>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                                
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection
@section('script')

@endsection
