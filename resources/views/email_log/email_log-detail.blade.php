@extends('layouts.app')

@php $title = CourseClass::get_training_name($mail_log->training_id); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('email_log_index') }}">ประวัติการส่งอีเมล์</a></li>
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
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body card-dashboard">

            <div class="row text-right">
              <div class="col-12">
                <a href="{{ route('email_log_detail', [ 'mail_log_id' => $mail_log->_id, 'platform'=>'excel' ]) }}"  class="btn btn-social width-200 mb-1 btn-outline-github text-center">
                  <span class="fa fa-file-excel-o font-medium-4"></span> Export Excel
                </a>
              </div>
            </div>

            <div class="table-responsive">
              <table id="table" class="table table-striped table-bordered zero-configuration">
                <thead>
                  <tr class="">
                  <th class="text-center align-middle">#</th>
                  <th class="text-center align-middle">EmployeeId</th>
                  <th class="text-center align-middle">Tinitial</th>
                  <th class="text-center align-middle">TFName</th>
                  <th class="text-center align-middle">TLName</th>
                  <th class="text-center align-middle">Workplace</th>
                  <th class="text-center align-middle">TitleName</th>
                  <th class="text-center align-middle">DivisionName</th>
                  <th class="text-center align-middle">SectionName</th>
                  <th class="text-center align-middle">DeptName</th>
                  <th class="text-center align-middle">BranchName</th>
                  <th class="text-center align-middle">Region</th>
                  <th class="text-center align-middle">StaffGrade</th>
                  <th class="text-center align-middle">JobFamily</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($employee))
                    @foreach($employee as $data)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
      
                      @if(!empty($data->employee_id)) 
                        <td class="text-center">{{ $data->employee_id }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->tinitial)) 
                        <td class="text-center">{{ $data->tinitial }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->tf_name)) 
                        <td class="text-center">{{ $data->tf_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->tl_name)) 
                        <td class="text-center">{{ $data->tl_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->workplace)) 
                        <td class="text-center">{{ $data->workplace }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->title_name)) 
                        <td class="text-center">{{ $data->title_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->division_name)) 
                        <td class="text-center">{{ $data->division_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->section_name)) 
                        <td class="text-center">{{ $data->section_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->dept_name)) 
                        <td class="text-center">{{ $data->dept_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->branch_name)) 
                        <td class="text-center">{{ $data->branch_name }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->region)) 
                        <td class="text-center">{{ $data->region }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->staff_grade)) 
                        <td class="text-center">{{ $data->staff_grade }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif
      
                      @if(!empty($data->job_family)) 
                        <td class="text-center">{{ $data->job_family }}</td>
                      @else 
                        <td class="text-center">-</td>
                      @endif

                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan=99 class="text-center">ไม่มีข้อมูล</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('style')
  <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/tables/datatable/datatables.min.css')}}"> 
  <style>
    .no-js #loader { display: none;  }
    .js #loader { display: block; position: absolute; left: 100px; top: 0; }
    .se-pre-con {
      position: fixed;
      /* opacity: .8; */
      left: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      z-index: 9999;
      background: url("{{ asset('Images/static/preload.gif') }}") center no-repeat #fff;
    }
    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right;
    }
    .o-scroll {
      overflow: scroll;
    }
    thead input {
      width: 100%;
    }
    </style>
@endsection

@section('script')
  <script src="{{asset('stack-admin/app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
  <script>
    $(document).ready(function() {
      // Setup - add a text input to each footer cell
      $('#table thead tr').clone(true).appendTo( '#table thead' );
      $('#table thead tr:eq(1) th').each( function (i) {
        if(i!=0) {
          var title = $(this).text();
          $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  
          $( 'input', this ).on( 'keyup change', function () {
              if ( table.column(i).search() !== this.value ) {
                  table
                      .column(i)
                      .search( this.value )
                      .draw();
              }
          } );
        } else {
          $(this).html('')
        }
      } );
  
      var table = $('#table').DataTable( {
          // orderCellsTop: true,
          fixedHeader: true
      } );
    } );
  </script>
@endsection