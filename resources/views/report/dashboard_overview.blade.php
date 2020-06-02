@extends('layouts.app')

@php $title = strtoupper('Dashboard Overview'); @endphp

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
  <div class="btn-group float-md-right mb-2 w-100" role="group" aria-label="Button group with nested dropdown">
    <form action="{{route('report_dashboard_overview')}}" class=" w-100" method="POST">
      {{ csrf_field() }}
      <div class="form-group">
        <label>ช่วงวันที่ดูข้อมูล</label>
        <div class='input-group'>
          <input id="datepicker" type='text' class="form-control daterange" name="date" />
          <div class="input-group-append">
            {{-- <span class="input-group-text">
              <span class="fa fa-calendar"></span>
            </span> --}}
            <button class="input-group-text" type="submit">
              ค้นหา
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-6 col-sm-12">
      <div class="card" id="pie">
        <div class="card-header">
          <h4 class="card-title text-center">&nbsp;</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-1" class="height-500 echart-container"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-sm-12">
      <div class="card" id="pie">
        <div class="card-header">
          <h4 class="card-title text-center">จำนวนพนักงานทั้งสิ้น {{ number_format($all_employee) }} คน</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-2" class="height-500 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row row-eq-heigt">
    <div class="col-lg-6 col-sm-12 mb-2">

      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title">หลักสูตรมาตรฐาน {{ array_sum($course_category['standard']) }} หลักสูตร</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div class="row">
              @foreach ($course_category['standard'] as $category_id => $total)
                <div class="col-6">
                  <div class="course-category mb-1">
                    <div class="category--name">{{ CourseClass::get_name_category($category_id) }}</div>
                    <div class="category--total">{{ $total }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="col-lg-6 col-sm-12 mb-2">

      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title">หลักสูตรทั่วไป {{ array_sum($course_category['general']) }} หลักสูตร</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div class="row">
              @foreach ($course_category['general'] as $category_id => $total)
                <div class="col-6">
                  <div class="course-category mb-1">
                    <div class="category--name">{{ CourseClass::get_name_category($category_id) }}</div>
                    <div class="category--total">{{ $total }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    </div>
    
  </div>

  <div class="row row-eq-heigt">
    <div class="col-12 mb-2">

      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title">หลักสูตรมาตรฐาน</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            @php
              $total_course = count($course_standard_stat['label']);   
              $size = $total_course * 55; 
              if($size < 150) {
                $size = 150;
              }
            @endphp
            <div id="chart-3" class="echart-container" style="height: {{$size}}px;"></div>
          </div>
        </div>
      </div>

    </div>
    <div class="col-12 mb-2">

      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title">หลักสูตรทั่วไป</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            @php
            $total_course = count($course_general_stat['label']);   
            $size = $total_course * 55; 
            if($size < 150) {
              $size = 150;
            }
          @endphp
          <div id="chart-4" class="echart-container" style="height: {{$size}}px;"></div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Device</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                <span class="card-icon primary d-flex justify-content-center mr-3">
                  <i class="icon p-1 customize-icon font-large-2 p-1 fa fa-desktop"></i>
                </span>
                <div class="stats-amount mr-3 device-detail ">
                  <h3 class="heading-text text-bold-600">{{ $device['desktop'] }}%</h3>
                  <p class="sub-heading">Desktop</p>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="d-flex align-items-start mb-sm-1 mb-xl-0 border-right-blue-grey border-right-lighten-5">
                <span class="card-icon danger d-flex justify-content-center mr-3">
                  <i class="icon p-1 customize-icon font-large-2 p-1 fa fa-mobile"></i>
                </span>
                <div class="stats-amount mr-3 device-detail ">
                  <h3 class="heading-text text-bold-600">{{ $device['mobile'] }}%</h3>
                  <p class="sub-heading">Mobile</p>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
              <div class="d-flex align-items-start ">
                <span class="card-icon success d-flex justify-content-center mr-3">
                  <i class="icon p-1 customize-icon font-large-2 p-1 fa fa-tablet"></i>
                </span>
                <div class="stats-amount mr-3 device-detail ">
                  <h3 class="heading-text text-bold-600">{{ $device['tablet'] }}%</h3>
                  <p class="sub-heading">Tablet</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="row">
        <div class="col-12 mb-2">
          <div class="card h-100">
            <div class="card-header">
              <h4 class="card-title text-center">จำนวนผู้เข้าเรียนของบริษัทนั้นๆ</h4>
            </div>
            <div class="card-content collapse show">
              <div class="card-body">
                <div id="chart-5" class="height-400 echart-container"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 mb-2">
          <div class="card h-100">
            <div class="card-header">
              <h4 class="card-title text-center">จำนวนผู้เข้าเรียนของบริษัท 3BB</h4>
            </div>
            <div class="card-content collapse show">
              <div class="card-body">
                <div id="chart-6" class="height-400 echart-container"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-xl-6 mb-2">
      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title text-center">หลักสูตรที่ได้รับความนิยม (หลักสูตรทั่วไป)</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-7" class="height-400 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-6 mb-2">
      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title text-center">พนักงานเข้าเรียนมากที่สุด</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-8" class="height-400 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-6 mb-2">
      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title text-center">แผนกที่พนักงานเข้าเรียนมากที่สุด</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-9" class="height-400 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-6 mb-2">
      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title text-center">Job family ที่พนักงานเข้าเรียนมากที่สุด</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-10" class="height-400 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-xl-6 mb-2">
      <div class="card h-100">
        <div class="card-header">
          <h4 class="card-title text-center">ผู้เรียนที่สำเร็จหลักสูตรมากที่สุด</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="chart-11" class="height-400 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/pickers/daterange/daterangepicker.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
  <style>
    .course-category {
      display: flex;
      justify-content: space-between;
    }
    .device {
      display: flex;
      justify-content: space-between;
    }
    .device-detail {
      display: flex;
      flex-direction: column;
      align-self: center;
    }
    .sub-heading {
      margin-bottom: 0;
    }
    .customize-icon {
      background-color: #F5F7FA;
      width: 70px;
      text-align: center;
    }
    .echart-container {
      min-height: 120px;
    }
  </style>
@endsection

@section('script')
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
  {{-- <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/echarts/echarts.js') }}" type="text/javascript"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/echarts@4.6.0/dist/echarts.min.js"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN STACK JS-->
  <script src="{{ asset('stack-admin/app-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/core/app.js') }}" type="text/javascript"></script>
  <!-- END STACK JS-->
  <script>
    $(function() {
      var start = moment("{{ $date_start }}"); //.subtract(6, 'days');
      var end = moment("{{ $date_end }}");
      function cb(start, end) {
        $('#datepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }
      $('#datepicker').daterangepicker({
        startDate: start,
        endDate: end,
        locale: {
          format: 'DD/MM/YYYY'
        },
        // format: 'DD/MMM/YYYY'
      }, cb);
      cb(start, end);
    });
    chart1()
    chart2()
    chart3()
    chart4()
    chart5()
    chart6()
    chart7()
    chart8()
    chart9()
    chart10()
    chart11()

    function number_format(data) {
      var value = data.value
      var result = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
      return result
    }

    function chart1() {
      var myChart = echarts.init(document.getElementById('chart-1'));
      var seriesLabel = {
        normal: {
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14,
          formatter: '{b} {c} หลักสูตร',
          position: 'inside'
        }
      }
      option = {
        tooltip: {
          trigger: 'item',
          formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          orient: 'vertical',
          left: 'left',
          data: JSON.parse(`{!! json_encode($course_type['label']) !!}`),
        },
        color: ['#ff66ff', '#00b0f0'],
        series: [
          {
            name: 'พนักงาน',
            type: 'pie',
            radius: '70%',
            center: ['50%', '50%'],
            label: seriesLabel,
            minAngle: 30,
            data: JSON.parse(`{!! json_encode($course_type['total']) !!}`),
            emphasis: {
              itemStyle: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.2)'
              }
            }
          }
        ]
      };

      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart2() {
      var myChart = echarts.init(document.getElementById('chart-2'));
      var seriesLabel = {
        normal: {
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14,
          formatter: '{d}%',
          position: 'inside'
        }
      }
      option = {
          tooltip: {
              trigger: 'item',
              formatter: '{a} <br/>{b} : {c} ({d}%)'
          },
          toolbox: {
            show: true,
            feature: {
              saveAsImage: {
                show: true,
                title: 'Download',
                name: Date.now(),
                lang: ['Save']
              }
            }
          },
          legend: {
            textStyle: {
            fontSize: 16
          },
              orient: 'vertical',
              left: 'left',
              data: JSON.parse(`{!! json_encode($employee_stat['label']) !!}`),
          },
          color: ['#5b9bd5', '#ed7d31', '#a5a5a5'],
          series: [
            {
              name: 'พนักงาน',
              type: 'pie',
              radius: '70%',
              center: ['50%', '50%'],
              label: seriesLabel,
              minAngle: 30,
              data: JSON.parse(`{!! json_encode($employee_stat['total']) !!}`),
              emphasis: {
                itemStyle: {
                  shadowBlur: 10,
                  shadowOffsetX: 0,
                  shadowColor: 'rgba(0, 0, 0, 0.2)'
                }
              }
            }
          ]
      };

      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart3() {
      var myChart = echarts.init(document.getElementById('chart-3'));
      var seriesLabel = {
        normal: {
          show: true,
          formatter: function(data) { 
            return number_format(data)
          },
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14,
          position: 'inside'
        }
      }
      option = {
        tooltip: {
          trigger: 'axis',
          formatter: '{a}: {c}',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['ยังไม่เข้าเรียน', 'เข้าเรียนแล้ว', 'เรียนสำเร็จ']
        },
        // Add custom colors
        color: ['#5b9bd5', '#ed7d31', '#a5a5a5'],
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'value',
          max: function (value) {
            return value.max + 1000;
          }
        },
        yAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($course_standard_stat['label']) !!}`),
        },
        series: [
          {
            name: 'ยังไม่เข้าเรียน',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($course_standard_stat['inactive']) !!}`)
          },
          {
            name: 'เข้าเรียนแล้ว',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($course_standard_stat['active']) !!}`)
          },
          {
            name: 'เรียนสำเร็จ',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($course_standard_stat['success']) !!}`)
          },
        ]
      };
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart4() {
      var myChart = echarts.init(document.getElementById('chart-4'));
      var seriesLabel = {
        normal: {
          show: true,
          formatter: function(data) { 
            return number_format(data)
          },
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14,
          position: 'inside'
        }
      }
      option = {
        tooltip: {
          trigger: 'axis',
          formatter: '{a}: {c}',
          axisPointer: {            // 坐标轴指示器，坐标轴触发有效
            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['ยังไม่เข้าเรียน', 'เข้าเรียนแล้ว', 'เรียนสำเร็จ']
        },
        // Add custom colors
        color: ['#5b9bd5', '#ed7d31', '#a5a5a5'],
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'value',
          max: function (value) {
            return value.max + 2000;
          }
        },
        yAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($course_general_stat['label']) !!}`),
        },
        series: [
          {
            name: 'ยังไม่เข้าเรียน',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($course_general_stat['inactive']) !!}`)
          },
          {
            name: 'เข้าเรียนแล้ว',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($course_general_stat['active']) !!}`)
          },
          {
            name: 'เรียนสำเร็จ',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($course_general_stat['success']) !!}`)
          },
        ]
      };
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart5() {
      var myChart = echarts.init(document.getElementById('chart-5'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14,
          formatter: '{c}%'
        }
      }
      option = {
        tooltip: {
          trigger: 'axis',
          formatter: '{b}: {c}%',
          axisPointer: {
            type: 'shadow'
          }
        },
        color: ['#ed7d31'],
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($company_stat['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            rotate: 20
          }
        },
        yAxis: {
          type: 'value',
          axisLabel: {
            formatter: '{value}%'
          }
        },
        series: [{
          data: JSON.parse(`{!! json_encode($company_stat['total']) !!}`),
          type: 'line',
          // label: seriesLabel
        }]
      };
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart6() {
      var myChart = echarts.init(document.getElementById('chart-6'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14,
          formatter: '{c}%'
        }
      }
      option = {
        tooltip: {
          trigger: 'axis',
          formatter: '{b}: {c}%',
          axisPointer: {
            type: 'shadow'
          }
        },
        color: ['#ed7d31'],
        grid: {
          left: '3%',
          right: '4%',
          bottom: '3%',
          containLabel: true
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($company_3bb_stat['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            rotate: 20
          }
        },
        yAxis: {
          type: 'value',
          axisLabel: {
            formatter: '{value}%'
          }
        },
        series: [{
          data: JSON.parse(`{!! json_encode($company_3bb_stat['total']) !!}`),
          type: 'line',
          // label: seriesLabel
        }]
      };
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart7() {
      var myChart = echarts.init(document.getElementById('chart-7'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14
        }
      }

      option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['หลักสูตรที่ได้รับความนิยม']
        },
        // Add custom colors
        color: ['#5b9bd5','#ed7d31','#ff66ff','#548235','#00b0f0'],
        grid: {
          left: 120, bottom: 120
        },
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($top5_course_general['label']) !!}`),
          nameLocation: 'middle',
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            formatter: '{value}',
            rotate: 20
          }
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name:'หลักสูตรที่ได้รับความนิยม',
            type:'bar',
            showBackground: true,
            backgroundStyle: {
              color: 'rgba(220, 220, 220, 0.8)'
            },
            stack: 'Total',
            barMinHeight: 50,
            // itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
            data: JSON.parse(`{!! json_encode($top5_course_general['total']) !!}`),
            label: seriesLabel
          }
        ]
      }
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart8() {
      var myChart = echarts.init(document.getElementById('chart-8'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14
        }
      }

      option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['พนักงานเข้าเรียนมากที่สุด']
        },
        // Add custom colors
        color: ['#5b9bd5','#ed7d31','#ff66ff','#548235','#00b0f0'],
        grid: {
          left: 120, bottom: 120
        },

        
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($top5_employee['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            formatter: '{value}',
            rotate: 20
          }
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name:'พนักงานเข้าเรียนมากที่สุด',
            type:'bar',
            showBackground: true,
            backgroundStyle: {
              color: 'rgba(220, 220, 220, 0.8)'
            },
            stack: 'Total',
            barMinHeight: 50,
            // itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
            data: JSON.parse(`{!! json_encode($top5_employee['total']) !!}`),
            label: seriesLabel
          }
        ]
      }
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart9() {
      var myChart = echarts.init(document.getElementById('chart-9'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14
        }
      }

      option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['แผนกที่พนักงานเข้าเรียนมากที่สุด']
        },
        // Add custom colors
        color: ['#5b9bd5','#ed7d31','#ff66ff','#548235','#00b0f0'],
        grid: {
          left: 120, bottom: 120
        },

        
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($top5_department['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            formatter: '{value}',
            rotate: 20
          }
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name:'แผนกที่พนักงานเข้าเรียนมากที่สุด',
            type:'bar',
            showBackground: true,
            backgroundStyle: {
              color: 'rgba(220, 220, 220, 0.8)'
            },
            stack: 'Total',
            barMinHeight: 50,
            // itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
            data: JSON.parse(`{!! json_encode($top5_department['total']) !!}`),
            label: seriesLabel
          }
        ]
      }
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart10() {
      var myChart = echarts.init(document.getElementById('chart-10'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14
        }
      }

      option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['Job family ที่พนักงานเข้าเรียนมากที่สุด']
        },
        // Add custom colors
        color: ['#5b9bd5','#ed7d31','#ff66ff','#548235','#00b0f0'],
        grid: {
          left: 120, bottom: 120
        },

        
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($top5_job_family['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            formatter: '{value}',
            rotate: 20
          }
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name:'Job family ที่พนักงานเข้าเรียนมากที่สุด',
            type:'bar',
            showBackground: true,
            backgroundStyle: {
              color: 'rgba(220, 220, 220, 0.8)'
            },
            stack: 'Total',
            barMinHeight: 50,
            // itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
            data: JSON.parse(`{!! json_encode($top5_job_family['total']) !!}`),
            label: seriesLabel
          }
        ]
      }
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }

    function chart11() {
      var myChart = echarts.init(document.getElementById('chart-11'));
      var seriesLabel = {
        normal: {
          show: true,
          color: '#000',
          textBorderColor: '#333',
          fontSize: 14
        }
      }

      option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'shadow'
          }
        },
        legend: {
          textStyle: {
            fontSize: 16
          },
          data: ['ผู้เรียนที่สำเร็จหลักสูตรมากที่สุด']
        },
        // Add custom colors
        color: ['#5b9bd5','#ed7d31','#ff66ff','#548235','#00b0f0'],
        grid: {
          left: 120, bottom: 120
        },

        
        toolbox: {
          show: true,
          feature: {
            saveAsImage: {
              show: true,
              title: 'Download',
              name: Date.now(),
              lang: ['Save']
            }
          }
        },
        xAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($top5_playend_all_ep['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            formatter: '{value}',
            rotate: 20
          }
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            name:'ผู้เรียนที่สำเร็จหลักสูตรมากที่สุด',
            type:'bar',
            showBackground: true,
            backgroundStyle: {
              color: 'rgba(220, 220, 220, 0.8)'
            },
            stack: 'Total',
            barMinHeight: 50,
            // itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
            data: JSON.parse(`{!! json_encode($top5_playend_all_ep['total']) !!}`),
            label: seriesLabel
          }
        ]
      }
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);

        // Resize function
        function resize() {
          setTimeout(function() {
            // Resize chart
            myChart.resize();
          }, 200);
        }
      });
    }
  </script>
@endsection