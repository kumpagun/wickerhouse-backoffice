@extends('layouts.app')

@php $title = "หนังสือรับรองการฝึกอบรม"; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
@endsection

@section('content')
<div id="spinner" class="mockup-loading">
  <i class="fas fa-spinner fa-spin fa-3x"></i>
</div>
<div class="card">
  <div class="card-content collapse show">
    <div class="card-body card-dashboard">

      <div class="o-scroll mt-2">
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
            <th class="text-center align-middle">Company</th>
            <th class="text-center align-middle">Region</th>
            <th class="text-center align-middle">StaffGrade</th>
            <th class="text-center align-middle">JobFamily</th>
            </tr>
          </thead>
          <tbody>
            @if(count($datas))
              @foreach($datas as $data)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>

                @if(!empty($data->employee_id)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->employee_id }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->tinitial)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->tinitial }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->tf_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->tf_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->tl_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->tl_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->workplace)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->workplace }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->title_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->title_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->division_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->division_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->section_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->section_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->dept_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->dept_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->branch_name)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->branch_name }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->company)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->company }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->region)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->region }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->staff_grade)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->staff_grade }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
                @endif

                @if(!empty($data->job_family)) 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">{{ $data->job_family }}</a></td>
                @else 
                  <td class="text-center"><a href="{{ route('report_training_certificate',['employee_id'=>$data->employee_id]) }}">-</a></td>
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
@endsection

@section('style')
<!-- Select 2 -->
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css')}}">
{{-- Datatable --}}
<link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/tables/datatable/datatables.min.css')}}">
<link rel="stylesheet" href="{{ asset('fontawesome-5.12.0/css/all.css') }}" />
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
  .mockup-loading {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    background: RGBA(255,255,255,0.9);
    padding: 10px;
    display: flex;
    align-content: center;
    justify-content: center;
    align-items: center;
    z-index: 999;
  }
  .fa-spinner {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
</style>
@endsection

@section('script')
{{-- Datatable --}}
<script src="{{asset('stack-admin/app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
{{-- <script src="{{asset('stack-admin/app-assets/js/scripts/tables/datatables/datatable-basic.js') }}" type="text/javascript"></script> --}}
<!-- pagination -->
<script>
  $(document).ready(function () {
    $('#spinner').hide();
  });
</script>
<script>
  $('.pagination li').addClass('page-item');
  $('.pagination li a').addClass('page-link');
  $('.pagination span').addClass('page-link');
  var t = $('.file-export').DataTable({
    dom: 'Bfrtip',
    lengthMenu: [
      [ 25, 50, 100, 200, -1 ],
      [ '25 rows', '50 rows', '100 rows', '200 rows', 'All' ]
    ]
  });
  t.on( 'order.dt search.dt', function () {
    t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
      cell.innerHTML = i+1;
    } );
  } ).draw();

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