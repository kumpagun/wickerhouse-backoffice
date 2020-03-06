@extends('layouts.app')

@if(!empty($data->id))
  @php $title = strtoupper('แก้ไขรอบอบรม'); @endphp
@else 
  @php $title = strtoupper('เพิ่มรอบอบรม'); @endphp
@endif

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
<div class="row align-items-center justify-content-center mb-2">
  <div class="col-12 col-md-10 col-lg-10 col-xl-8">
    @if (session('success'))
    <div class="alert bg-success alert-icon-left alert-dismissible mb-2" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <strong>Success</strong> บันทึกเรียบร้อยแล้ว
    </div>
    @endif
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0">
        <div class="card-title text-center"> รายละเอียดรอบอบรมทั้งหมด </div>
      </div>
      <div class="card-content ">
        <div class="card-body py-0 ">
          <form class="form-group row" action="{{ route('training_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="col-12">
              <fieldset class="form-group @if($errors->training->has('title')) danger @endif">
              <label for="user-name">ชื่อรอบการอบรม *</label>
              <input type="text" name="title" class="form-control" value="{{ old('title', $data->title) }}" placeholder="รอบการอบรม" required>
              @if($errors->training->has('title'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->training->first('title') }}</p>
                  </span>
              @endif
              </fieldset>
            </div>
            <div class="col-12">
              <fieldset class="form-group @if($errors->training->has('course_id')) danger @endif">
                <label for="user-name">ชื่อคอร์ส *</label>
                <select class="select2 form-control" name="course_id" @if($data->id != '')disabled @endif required>
                  <option value=""> กรุณาเลือกคอร์ส</option>
                  @foreach ($course as $item )
                    <option value={{ $item }} 
                      @if(((string)$data->course_id == (string)$item)) selected  @endif
                    >{{ CourseClass::get_name_course($item) }}</option>
                  @endforeach
                </select>
                @if($errors->training->has('course_id'))
                    <span class="small" role="alert">
                    <p class="mb-0">{{ $errors->training->first('course_id') }}</p>
                    </span>
                @endif
              </fieldset>
            </div>
            {{-- <div class="col-6">
              <fieldset class="form-group @if($errors->training->has('company_id')) danger @endif">
              <label for="user-name">Company</label>
              <select class="select2 form-control" id="div_content" name="company_id" onchange="handleCompany(this.value)">
                <option value=""> กรุณาเลือก Company</option>
                @foreach ($company as $item )
                  <option value={{ $item }} 
                    @if( !empty($data->company_id)  && ((string)$data->company_id == (string)$item)) selected  @endif >{{ FuncClass::get_name_company($item) }} 
                  </option>
                @endforeach
              </select>
              @if($errors->training->has('company_id'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->training->first('company_id') }}</p>
                  </span>
              @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->training->has('title')) danger @endif">
                <label for="user-name">Department</label>
                <select class="select2 form-control" name="dept_name" multiple="multiple">
                </select>
              </fieldset>
            </div> --}}
            <div class="col-6">
              <fieldset class="form-group @if($errors->training->has('published_at')) danger @endif">
                <label for="user-name">วันที่เริ่มใช้งาน *</label>
                <div class='input-group date published_at'  id='datetimepicker'>
                  <input type='text' class="form-control" name="published_at" @if(!empty($data->published_at)) value="{{old('published_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->published_at))}}" @else  value="{{old('published_at')}}" @endif required/> 
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <span class="fa fa-calendar"></span>
                    </span>
                  </div>
                </div>
                @if($errors->training->has('published_at'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->training->first('published_at') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-6">
              <fieldset class="form-group @if($errors->training->has('expired_at')) danger @endif">
                <label for="user-name">วันที่สิ้นสุดการใช้งาน *</label>
                <div class='input-group date expired_at'  id='datetimepicker'>
                  <input type='text' class="form-control" name="expired_at" @if(!empty($data->expired_at)) value="{{old('expired_at',FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($data->expired_at))}}" @else  value="{{old('expired_at')}}" @endif required/> 
                  <div class="input-group-append">
                    <span class="input-group-text">
                      <span class="fa fa-calendar"></span>
                    </span>
                  </div>
                </div>
                @if($errors->training->has('expired_at'))
                  <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->training->first('expired_at') }}</p>
                  </span>
                @endif
              </fieldset>
            </div>
            <div class="col-12">
              @can('editor')
                <button type="submit" class="btn btn-primary btn-block">บันทึก</button>
              @else
                <button  type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>NOT ALLOW</button>
              @endcan
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@if(!empty($data->id))
<div class="row justify-content-center mb-2">
  <div class="col-12 col-md-10 col-lg-10 col-xl-8">
    <a href="{{ route('traingin_user_list', ['id' => $data->id]) }}">ดูรายชื่อพนักงาน {{ FuncClass::count_user_in_traingin($data->_id) }} คน</a>
  </div>
</div>
<div class="row align-items-center justify-content-center pb-2">
  <div class="col-12 col-md-10 col-lg-10 col-xl-8">
    <div class="card px-1 py-2 m-0">
      <div class="card-header border-0">
        <div class="card-title text-center"> เลือกพนักงาน </div>
      </div>
      <div class="card-content ">
        <div class="card-body py-0 ">
          <h6 class="card-subtitle line-on-side text-muted text-center font-small-3">
            <span>Import Excel</span>
          </h6>
          <div class="row mb-1">
            <div class="col-12">
              @can('editor')
              <button type="button" class="btn btn-outline-secondary btn-min-width"  aria-hidden="true" aria-label="Close" data-toggle="modal" data-target="#AnswerModal{{$data->_id}}">
                Import File Excel
              </button>
              @else
                <button type="button" class="btn btn-outline-secondary btn-min-width"  data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>
                  Import File Excel
                </button>
              @endcan
              <p class="text-danger my-1">* Import File Excel เพิ่มเพิ่มพนักงานที่ต้องการให้เข้าอบรมในรอบนี้</p>
            </div>
          </div>
          <h6 class="card-subtitle line-on-side text-muted text-center font-small-3">
            <span>เลือกแบบใส่เงื่อนไข</span>
          </h6>
          <div class="row">
            <div class="col-12 col-sm-6">
              <fieldset class="form-group">
                <label for="user-name">รหัสพนักงาน</label>
                <input type="text" class="form-control" id="employee_id" name="employee_id" />
              </fieldset>
            </div>
            <div class="col-12 col-sm-6">
              <fieldset class="form-group">
                <label for="user-name">ชื่อพนักงาน</label>
                <input type="text" class="form-control" id="employee_name" name="employee_name" />
              </fieldset>
            </div>
            <div class="col-12 col-sm-6">
              <fieldset class="form-group">
                <label for="user-name">บริษัท</label>
                <select class="select2 form-control" id="company_name" name="company_name">
                  <option value="">กรุณาบริษัท</option>
                  @foreach ($company as $item )
                  <option value={{ $item }} 
                    @if( !empty($data->company_id)  && ((string)$data->company_id == (string)$item)) selected  @endif >{{ FuncClass::get_name_company($item) }} 
                  </option>
                @endforeach
                </select>
              </fieldset>
            </div>
            <div class="col-12 col-sm-6">
              <fieldset class="form-group">
                <label for="user-name">หน่วยงาน</label>
                <div id="dept_name-loading"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
                <div id="dept_name-container">
                  <select class="select2 form-control" id="dept_name" name="dept_name"></select>
                </div>
              </fieldset>
            </div>
            <div class="col-12">
              <div class="form-group mb-2 longevity-repeater">
                <label for="user-name">อายุงาน</label>
                <div data-repeater-list="longevity">
                  <div class="mb-1" data-repeater-item>
                    <div class="row">
                      <div class="col-4">
                        <select class="form-control" name="longevity_condition">
                          <option value="">กรุณาเลือกเงื่อนไข</option>
                          <option value="<">มากกว่า</option>
                          <option value="<=">มากกว่าหรือเท่ากับ</option>
                          <option value=">">น้อยกว่า</option>
                          <option value=">=">น้อยกว่าหรือเท่ากับ</option>
                          <option value="=">เท่ากับ</option>
                        </select>
                      </div>
                      <div class="col-8 input-group ">
                        <input type="number" name="longevity" placeholder="อายุงาน (ปี)" class="form-control">
                        <div class="input-group-append">
                          <span class="input-group-btn" id="button-addon2">
                            <button class="btn btn-danger" type="button" data-repeater-delete><i class="feather icon-x"></i></button>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <button type="button" data-repeater-create class="btn btn-outline-secondary">
                    <i class="ft-plus"></i> เพิ่มเงื่อนไข
                  </button>
                </div>
              </div>
            </div>
            <div class="col-12 my-2">
              <button class="btn btn-outline-secondary" onclick="search_result()">ค้นหา</button>
            </div>
          </div>
          <form class="form-group" action="{{ route('training_import_employees') }}" method="POST">
            @csrf
            <input type="hidden" name="training_id" value="{{ $data->id }}">
            <div class="row mb-2">
              <div class="col-12 text-center div-loading">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
              </div>
              <div class="col-12 div-employee">
                <div class="mockup-loading">
                  <i class="fas fa-spinner fa-spin fa-2x"></i>
                </div>
                <select multiple="multiple" class="employees" name="employees[]"></select>
                <div class="row mb-2">
                  <div class="col-6 text-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="select_all()">เลือกทั้งหมด</button>
                  </div>
                  <div class="col-6 text-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="delete_all()">ลบทั้งหมด</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    @can('editor')
                    <button type="submit" class="btn btn-block btn-secondary">บันทึก</button>
                    @endcan
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

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
              <input name="excel" class="form-control" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
            </div>
            @if($errors->first('excel'))<p><small class="danger text-muted">{{$errors->first('excel')}}</small></p>@endif
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
            <a href="{{ asset("Files/example.xlsx") }}">ตัวอย่างไฟล์สำหรับ import</a>
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
@endif
@endsection

@section('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
  {{-- <!-- Include Quill stylesheet -->
  <link rel="stylesheet" type="text/css" href="{{asset('css/quill.snow.css')}}"> --}}
  {{-- Date Time --}}
  <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('stack-admin/app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">

  <link href="{{ asset('multiselect/css/multi-select.css') }}" media="screen" rel="stylesheet" type="text/css">
  {{-- Fontawesome --}}
  <link rel="stylesheet" href="{{ asset('fontawesome-5.12.0/css/all.css') }}" />
  <style>
    .ms-container {
      width: 100%;
    }
    .div-employee {
      position: relative;
      padding: 15px;
    }
    .mockup-loading {
      position: absolute;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      background: RGBA(0,0,0,0.1);
      padding: 10px;
      display: flex;
      align-content: center;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }
  </style>
@endsection

@section('script')
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
{{-- <!-- Include the Quill library -->
<script src="{{ asset('js/quill.js')}}" type="text/javascript"></script> --}}
{{-- Include Time Date --}}
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js')}}" type="text/javascript"></script>
<script src="{{ asset('stack-admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('multiselect/js/jquery.multi-select.js') }}" type="text/javascript"></script>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> --}}
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script>
  $('.published_at').datetimepicker({
    format: 'DD-MM-YYYY'
  })
  $('.expired_at').datetimepicker({
    format: 'DD-MM-YYYY',
  })

  $('.longevity-repeater').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function(remove) {
      if (confirm('Are you sure you want to remove this item?')) {
        $(this).slideUp(remove);
      }
    }
  });

  $( document ).ready(function() {
    $('.div-loading').hide();
    $('.div-employee').hide();
    $('.mockup-loading').hide();
    $('#dept_name-loading').hide();
    $('#dept_name-container').hide();

    handleCompany()
    $('select[name="company_name"]').change(function () {
      var department_ids = [];
      var company_id = $(this).val()
      handleCompany(company_id,department_ids)
    })
  })
  
  function handleCompany(company_id,department_ids){
    if(company_id){
      $("[name='dept_name']").empty()
      $('#dept_name-loading').show();
      $('#dept_name-container').hide();
      var url = "{{ route('get_department_by_company') }}/"+company_id
      $.get(url, function (data) {
        $("[name='dept_name']").append($('<option>', {
          value: '',
          text: 'กรุณาเลือกหน่วยงาน'
        }));
        data.forEach(function (ch) {
          $("[name='dept_name']").append(
            $('<option> ', {
              value: ch.id,
              text: ch.topic
            })
          )
        })
        $("[name='dept_name']").attr('disabled', false)
      $('#dept_name-loading').hide();
      $('#dept_name-container').show();
      })
    } else {
      $("[name='dept_name']").find('option').remove()
      $("[name='dept_name']").append($('<option>', {
        value: '',
        text: 'กรุณาเลือกหน่วยงาน'
      }));
      $('#dept_name-loading').hide();
      $('#dept_name-container').show();
    }
  }

  function search_result() {
    $('.div-loading').show();
    $('.div-employee').hide();
    var type = "{{ Auth()->user()->type }}";
    var employee_id = $('#employee_id').val()
    var employee_name = $('#employee_name').val()
    var company_name = $('#company_name').val()
    var dept_name = $('#dept_name').val()
    var in_dept = $('#in_dept').val()
    var url = "{{ route('training_employee_filter') }}"
    var longevity = []
    var longevity_condition = []
    var count = 0
    jQuery('input[name*="longevity"]').each(function(e)
    {
      var result = parseInt($(this).val())
      if(result &&  Number.isInteger(result)) {
        longevity.push(result)
      }
    })
    jQuery('select[name*="longevity_condition"]').each(function(e)
    {
      var result = $(this).val()
      if(result) {
        longevity_condition.push(result) 
      }
    })

    if(longevity.length!=longevity_condition.length) {
      swal.fire('กรุณาใส่เงื่อนไข "อายุงาน" ให้ถูกต้อง')
      return false
    }

    if(!employee_id && !employee_name && !company_name && !dept_name && longevity.length==0) {
      swal.fire('กรุณากรอกอย่างน้อย 1 เงื่อนไข')
      $('.div-loading').hide();
      $('.div-employee').hide();
      return false
    }
    $("[name='employees[]']").empty()
    $.get(url, 
    {
      employee_id: employee_id,
      employee_name: employee_name,
      company_name: company_name,
      dept_name: dept_name,
      in_dept: in_dept,
      longevity: longevity,
      longevity_condition: longevity_condition
    },
    function(data, status){
      data.datas.map((values,index) => {
        $("[name='employees[]']").append(
          $('<option> ', {
            value: values.employee_id,
            text: values.tinitial+' '+ values.tf_name + ' ' + values.tl_name + ' ('+values.employee_id+')'
          })
        );
      })
      $('.employees').multiSelect('refresh',{ 
        keepOrder: true,
        afterSelect: function(value){
          $('[name="employees[]"] option[value="'+value+'"]').remove();
          $('[name="employees[]"]').append($("<option></option>").attr("value",value).attr('selected', 'selected'));
        }
      });
      if(data.datas.length==0) {
        $('.div-loading').hide();
        $('.div-employee').hide();
      } else {
        $('.div-loading').hide();
        $('.div-employee').show();
      }
    })
  }

  function select_all() {
    $('.mockup-loading').show(function(){
      $('.employees option').attr('selected', 'selected');
      $('.employees').multiSelect('refresh')
      $('.mockup-loading').hide()
    })
  }
  function delete_all() {
    $('.mockup-loading').show(function(){
      $('.employees option').attr('selected', false);
      $('.employees').multiSelect('refresh')
      $('.mockup-loading').hide()
    })
  }
</script>
@endsection
