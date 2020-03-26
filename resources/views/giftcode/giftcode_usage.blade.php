@extends('layouts.app')

@php $title = strtoupper('รายงานผู้โชคดีที่ได้รับรางวัล'); @endphp

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

@section('content')
<div class="card">
  <div class="card-content collapse show">
    <div class="card-body card-dashboard">
      <form class="row align-items-baseline justify-content-end" method="POST">
        {{ csrf_field() }}
        <label class="col-4 text-right"> รอบการอบรม</label>
        <div class="col-8">
          <div class="form-group">
            <select name="training_id" class="form-control select2" onchange="this.form.submit()">
              @foreach($training as $key)
                <option value="{{$key->_id}}" @if( $training_id == (string)($key->_id)) selected @endif>{{ $key->title}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </form>

      <div class="row text-right">
        <div class="col-12">
          <a href="{{ route('giftcode_usage', ['training_id'=>$training_id, 'export'=>'excel']) }}" class="btn btn-social width-200 mb-1 btn-outline-github text-center">
            <span class="fa fa-file-excel-o font-medium-4"></span> Export Excel
          </a>
        </div>
      </div>

      <div class="o-scroll mt-2">
        <table class="table table-striped table-bordered zero-configuration">
          <thead>
            <tr class="">
            <th class="text-center align-middle">#</th>
            <th class="text-center align-middle">Datetime</th>
            <th class="text-center align-middle">EmployeeId</th>
            <th class="text-center align-middle">Tinitial</th>
            <th class="text-center align-middle">TFName</th>
            <th class="text-center align-middle">TLName</th>
            <th class="text-center align-middle">Email</th>
            <th class="text-center align-middle">Workplace</th>
            <th class="text-center align-middle">TitleName</th>
            <th class="text-center align-middle">DivisionName</th>
            <th class="text-center align-middle">SectionName</th>
            <th class="text-center align-middle">DeptName</th>
            <th class="text-center align-middle">BranchName</th>
            </tr>
          </thead>
          <tbody>
            @if(count($datas))
              @foreach($datas as $data)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>

                <td class="text-center">{{ FuncClass::utc_to_carbon_format_time_zone_bkk($data->created_at) }}</td>
                @if(!empty($data->employees->employee_id)) 
                  <td class="text-center">{{ $data->employees->employee_id }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->tinitial)) 
                  <td class="text-center">{{ $data->employees->tinitial }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->tf_name)) 
                  <td class="text-center">{{ $data->employees->tf_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->tl_name)) 
                  <td class="text-center">{{ $data->employees->tl_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                <td class="text-center">{{ Member::getUserEmailFromEmployeeId($data->employees->employee_id) }}</td>

                @if(!empty($data->employees->workplace)) 
                  <td class="text-center">{{ $data->employees->workplace }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->title_name)) 
                  <td class="text-center">{{ $data->employees->title_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->division_name)) 
                  <td class="text-center">{{ $data->employees->division_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->section_name)) 
                  <td class="text-center">{{ $data->employees->section_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->dept_name)) 
                  <td class="text-center">{{ $data->employees->dept_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->employees->branch_name)) 
                  <td class="text-center">{{ $data->employees->branch_name }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="13" class="text-center">ไม่มีข้อมูล</td>
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
</style>
@endsection

@section('script')
{{-- Datatable --}}
<script src="{{asset('stack-admin/app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{asset('stack-admin/app-assets/js/scripts/tables/datatables/datatable-basic.js') }}" type="text/javascript"></script>
<!-- pagination -->
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

  $(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
	});
</script>
@endsection