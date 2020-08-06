@extends('layouts.app')

@php $title = strtoupper('รายงานรายบุคคล(ภูมิภาค)'); @endphp

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
      @if(Auth::user()->type=='jasmine' && !Auth::user()->hasRole('admin'))
      <div class="row">
        <div class="col-12">Name <span class="text-primary">{{ Auth::user()->user_info['thai_fullname'] }}</span></div>
        <div class="col-12">DivisionName <span class="text-primary">{{ Auth::user()->user_info['division'] }}</span></div>
        <div class="col-12">SectionName <span class="text-primary">{{ Auth::user()->user_info['section'] }}</span></div>
        <div class="col-12">DeptName <span class="text-primary">{{ Auth::user()->user_info['department'] }}</span></div>
      </div>
      @endif
      <form class="row align-items-baseline justify-content-end" action="{{route('report_access_content_by_director')}}" method="POST">
        {{ csrf_field() }}
        <div class="col-12 col-md-4">
          <div class="row align-items-baseline">
            <div class="col-12">
              <label class="text-left text-md-right"> ปี</label>
              <div class="form-group">
                <select name="search_year" class="form-control select2" onchange="handleChangeYear(this.value)">
                  @for($year=2019;$year<=date('Y');$year++)
                    <option value={{$year}} @if($search_year == $year) selected @endif>{{ $year }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="row align-items-baseline">
            <div class="col-12">
              <label class="text-left text-md-right"> หลักสูตร</label>
              <div class="form-group">
                <select name="search_group" class="form-control select2">
                  <option value="" @if(empty($search_group)) selected @endif>กรุณาเลือกหลักสูตร</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-8 col-md-2">
          <div class="row align-items-baseline">
            <div class="col-12">
              <label class="text-left text-md-right"> สถานะ</label>
              <div class="form-group">
                <select name="filter_status" class="form-control select2">
                  <option value="" @if(empty($filter_status)) selected @endif>ทั้งหมด</option>
                  <option value="active" @if('active' == $filter_status) selected @endif>เข้าเรียน</option>
                  <option value="inactive" @if('inactive' == $filter_status) selected @endif>ยังไม่เข้าเรียน</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4 col-md-2">
          <div class="row align-items-baseline">
            <div class="col-12">
              <label class="text-left text-md-right"> &nbsp;</label>
              <button type="submit" class="btn btn-block btn-primary">ค้นหา</button>
            </div>
          </div>
        </div>
      </form>

      <div class="row text-right">
        <div class="col-12">
          <a href="{{ route('report_access_content_by_director', ['search_group'=>$search_group, 'filter_status'=>$filter_status, 'platform'=>'excel']) }}"  class="btn btn-social width-200 mb-1 btn-outline-github text-center">
            <span class="fa fa-file-excel-o font-medium-4"></span> Export Excel
          </a>
        </div>
      </div>

      <div class="o-scroll mt-2">
        <table id="table" class="table table-striped table-bordered zero-configuration">
          <thead>
            <tr>
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
              <th class="text-center align-middle">Status</th>
              <th class="text-center align-middle">Pretest</th>
              <th class="text-center align-middle">Posttest</th>
              <th class="text-center align-middle">Course Complete</th>
            </tr>
          </thead>
          <tbody>
            @if(count($datas))
              @foreach($datas as $data)
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

                @if(!empty($data->firstname)) 
                  <td class="text-center">{{ $data->firstname }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->lastname)) 
                  <td class="text-center">{{ $data->lastname }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->workplace)) 
                  <td class="text-center">{{ $data->workplace }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->title)) 
                  <td class="text-center">{{ $data->title }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->division)) 
                  <td class="text-center">{{ $data->division }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->section)) 
                  <td class="text-center">{{ $data->section }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->department)) 
                  <td class="text-center">{{ $data->department }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->branch)) 
                  <td class="text-center">{{ $data->branch }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->company)) 
                  <td class="text-center">{{ $data->company }}</td>
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

                @if(!empty($data->play_course)) 
                  <td class="text-left text-success">เข้าเรียนแล้ว</td>
                @else 
                  <td class="text-left text-danger">ยังไม่เข้าเรียน</td>
                @endif

                @if(isset($data->pretest)) 
                <td class="text-center">{{ $data->pretest }}</td>
                @else 
                <td class="text-center">-</td>
                @endif

                @if(isset($data->posttest)) 
                  <td class="text-center">{{ $data->posttest }}</td>
                @else 
                  <td class="text-center">-</td>
                @endif

                @if(!empty($data->play_course_end)) 
                  <td class="text-center">{{ $data->play_course_end }}</td>
                @else 
                  <td class="text-center">0</td>
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
      {{-- <div class="text-center">
        {{ $datas->appends([ 'search_group' => $search_group ])->links() }}
      </div> --}}
      </div>
        {{-- <span class="text-danger"><small>* ยอด video view จาก embed (web, app) </small></span> --}}
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
thead input {
  width: 100%;
}
</style>
@endsection

@section('script')
{{-- Datatable --}}
<script src="{{asset('stack-admin/app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
{{-- <script src="{{asset('stack-admin/app-assets/js/scripts/tables/datatables/datatable-basic.js') }}" type="text/javascript"></script> --}}
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

<script>
  $(document).ready(function() {
    handleChangeYear('{{$search_year}}')
  })
  function handleChangeYear(year){
    if(year){
      $("[name='search_group']").empty()
      var url = "{{ route('report_get_course_from_year') }}/"+year
      $.get(url, function (data) {
        if(data.datas.length > 0) {
          $("[name='search_group']").append($('<option>', {
            value: '',
            text: 'กรุณาเลือกหลักสูตร'
          }));
          data.datas.forEach(function (ch) {
            var search_group = "{{ $search_group }}"
            if(search_group==ch._id) {
              $("[name='search_group']").append(
                $('<option> ', {
                  value: ch._id,
                  text: ch.title
                }).attr('selected', 'selected')
              )
            } else {
              $("[name='search_group']").append(
                $('<option> ', {
                  value: ch._id,
                  text: ch.title
                })
              )
            }
          })
        } else {
          $("[name='search_group']").append($('<option>', {
            value: '',
            text: 'ไม่มีหลักสูตร'
          }));
        }
        
        $("[name='search_group']").attr('disabled', false)
      })
    } else {
      $("[name='search_group']").find('option').remove()
      $("[name='search_group']").append($('<option>', {
        value: '',
        text: 'กรุณาเลือกหลักสูตร'
      }));
    }
  }
</script>
@endsection