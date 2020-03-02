@extends('layouts.app')

@php $title = strtoupper('Dashboard'); @endphp

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
  @if(count($query_group)>0)
  <div class="btn-group float-md-right mb-2" role="group" aria-label="Button group with nested dropdown">
    <form action="{{route('report_member_access_content_by_RO')}}" method="POST">
      {{ csrf_field() }}
      <label class="text-left"> Training List</label>
      <select name="search_group" class="form-control select2" onchange="this.form.submit()">
        @foreach($query_group as $key)
          <option value="{{$key->_id}}" @if( $search_group == (string)($key->_id)) selected @endif>{{ $key->title}}</option>
        @endforeach
      </select>
    </form>
  </div>
  @endif
@endsection

@section('content')
<!-- Basic Tables start -->
@if(!empty($datas))
  <div class="row align-items-stretch">
    <div class="col-12">
      <div class="card">
        <div class="card-content collapse show">
          <div class="card-body">
            <div class="row align-items-baseline">
              <div class="col-12">
                <h4 class="card-title">Member access content</h4>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="second-row">
                    <th class="align-middle text-center">DeptName</th>
                    <th class="align-middle text-center">เข้าเรียน</th>
                    <th class="align-middle text-center">เข้าเรียน(ผ่าน)</th>
                    <th class="align-middle text-center">เข้าเรียน(ไม่ผ่าน)</th>
                    <th class="align-middle text-center">ไม่เข้าเรียน</th>
                    <th class="align-middle text-center">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($datas))
                    @foreach($datas as $key => $value)
                    <tr>
                      <td class="">
                        {{ $key }}
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_active']))
                          {{ number_format($value['user_active']) }}
                        @else
                          0
                        @endif
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_active_passing_score']))
                          {{ number_format($value['user_active_passing_score']) }}
                        @else
                          0
                        @endif
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_active_not_passing_score']))
                          {{ number_format($value['user_active_not_passing_score']) }}
                        @else
                          0
                        @endif
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_inactive']))
                          {{ number_format($value['user_inactive']) }}
                        @else
                          0
                        @endif
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_active']) && !empty($value['user_inactive']))
                          {{ number_format($value['user_active'] + $value['user_inactive']) }}
                        @elseif(!empty($value['user_active']))
                          {{ number_format($value['user_active']) }}
                        @elseif(!empty($value['user_inactive']))
                          {{ number_format($value['user_inactive']) }}
                        @else
                          0
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td class="text-center" colspan="6">ไม่มีข้อมูล</td>
                    </tr>
                  @endif
                </tbody>
                @if(count($datas))
                <tfoot>
                  <tr>
                    <td class="text-right"><Strong>Total</Strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_active']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_active_passing_score']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_active_not_passing_score']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_inactive']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_active'] + $data_total['user_inactive']) }}</strong></td>
                  </tr>
                  @php
                    if($data_total['user_active']==0) {
                      $total_user_active_percent = 0;
                      $total_user_active_passing_percent = 0;
                      $total_user_active_not_passing_percent = 0;
                    } else {
                      $total_user_active_percent = ($data_total['user_active']/($data_total['user_active']+$data_total['user_inactive']))*100;
                      $total_user_active_passing_percent = ($data_total['user_active_passing_score']/$data_total['user_active'])*100;
                      $total_user_active_not_passing_percent = ($data_total['user_active_not_passing_score']/$data_total['user_active'])*100;
                    }
                    if($data_total['user_inactive']==0) {
                      $total_inactive_percent = 0;
                    } else {
                      $total_inactive_percent = ($data_total['user_inactive']/($data_total['user_inactive']+$data_total['user_active']))*100;
                    }
                    
                    $total_user_percent = 100;
                  @endphp
                  <tr>
                    <td class="text-right"><Strong>Total Percent</Strong></td>
                    <td class="text-right"><strong>{{ number_format($total_user_active_percent,2).'%' }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_user_active_passing_percent,2).'%' }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_user_active_not_passing_percent,2).'%' }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_inactive_percent,2).'%' }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($total_user_percent,2).'%' }}</strong></td>
                  </tr>
                </tfoot>
                @endif
              </table>
            </div>
              {{-- <span class="text-danger"><small>* ยอด video view จาก embed (web, app) </small></span> --}}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Basic Tables end -->

  <!-- Simple Pie Chart -->
  <div class="row justify-content-center">
    <div class="col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">สถานะผู้เข้าเรียน <div>หลักสูตร {{ $training_title }}</div><div>ณ วันที่ {{ $last_update }}</div></h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="simple-pie-chart" class="height-500 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Column Chart -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">สถานะผู้เข้าเรียนหลักสูตร {{ $training_title }} ณ วันที่ {{ $last_update }}</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            @php
              $value = $diff * 100;
            @endphp
            <div id="basic-bar" class="height-{{$value}} echart-container"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Column Stacked Chart -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">สถานะผู้เข้าเรียนหลักสูตร {{ $training_title }} ณ วันที่ {{ $last_update }}</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="stacked-bar" class="height-500 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Column Stacked Chart -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">สถานะผู้ไม่เข้าเรียนหลักสูตร {{ $training_title }} ณ วันที่ {{ $last_update }}</h4>
        </div>
        <div class="card-content collapse show">
          <div class="card-body">
            <div id="stacked-bar-inactive" class="height-500 echart-container"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

@else

<div class="row justify-content-center">
  <div class="col-12 col-md-8 col-lg-6">
    <div class="card">
      <div class="card-content collapse show">
        <div class="card-body">
          <div class="row align-items-baseline">
            <div class="col-12">
              <h4 class="card-title text-center my-1">ไม่มีข้อมูล</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection

@section('style')
  @for($i=1;$i<=$diff;$i++) 
    @php
      $value = $i * 100;
      echo "<style>.height-".$value." { height: ".$value."px; } </style>";
    @endphp
   
  @endfor
@endsection

@section('script')
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/echarts/echarts.js') }}" type="text/javascript"></script>ฃ
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN STACK JS-->
  <script src="{{ asset('stack-admin/app-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/core/app.js') }}" type="text/javascript"></script>
  <!-- END STACK JS-->
  <script>
    $(window).on("load", function(){

// Set paths
// ------------------------------

require.config({
    paths: {
        echarts: '/stack-admin/app-assets/vendors/js/charts/echarts'
    }
});


// Configuration
// ------------------------------

require(
    [
        'echarts',
        'echarts/chart/pie',
        'echarts/chart/funnel'
    ],


    // Charts setup
    function (ec) {
        // Initialize chart
        // ------------------------------
        var myChart = ec.init(document.getElementById('simple-pie-chart'));

        // Chart Options
        // ------------------------------
        chartOptions = {

            // Add title
            // title: {
            //     text: 'Browser popularity',
            //     subtext: 'Open source information',
            //     x: 'center'
            // },

            // Add tooltip
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },

            // Add legend
            legend: {
                orient: 'vertical',
                x: 'left',
                data: JSON.parse(`{!! json_encode($pie_chart['label']) !!}`)
            },

            // Add custom colors
            color: ['#16D39A', '#F98E76'],

            // Display toolbox
            toolbox: {
              show: true,
              orient: 'vertical',
              feature: {
                saveAsImage: {
                  show: true,
                  title: 'Save as image',
                  name: Date.now(),
                  lang: ['Save']
                }
              }
            },

            // Enable drag recalculate
            calculable: true,

            // Add series
            series: [{
              show: true,
                name: 'พนักงาน',
                type: 'pie',
                radius: '70%',
                center: ['50%', '57.5%'],
                data: JSON.parse(`{!! json_encode($pie_chart['data']) !!}`)
            }]
        };

        // Apply options
        // ------------------------------

        myChart.setOption(chartOptions);


        // Resize chart
        // ------------------------------

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
);
});
  </script>

  {{-- คนเข้าเรียน ไม่เข้าเรียน --}}
  <script>
    $(window).on("load", function(){

    // Set paths
    // ------------------------------

    require.config({
      paths: {
        echarts: '/stack-admin/app-assets/vendors/js/charts/echarts'
      }
    });

    // Configuration
    // ------------------------------

    require(
      [
        'echarts',
        'echarts/chart/bar',
        'echarts/chart/line'
      ],

      // Charts setup
      function (ec) {
        // Initialize chart
        // ------------------------------
        var myChart = ec.init(document.getElementById('basic-bar'));

        // Chart Options
        // ------------------------------
        chartOptions = {
          // Setup grid
          grid: {
            x: 70,
            x2: 40,
            y: 45,
            y2: 25
          },

          // Add tooltip
          tooltip: {
            trigger: 'axis'
          },

          // Add Toolbook
          toolbox: {
            show : true,
            // orient: 'vertical',
            x: 'right',
            // y: 70,
            feature : {
              saveAsImage: {
                show: true,
                title: 'Save as image',
                name: Date.now(),
                lang: ['Save']
              }
            }
          },

          // Add legend
          legend: {
            data: ['เข้าเรียน', 'ไม่เข้าเรียน']
          },

          // Add custom colors
          color: ['#16D39A', '#F98E76'],

          // Horizontal axis
          xAxis: [{
            type: 'value',
            boundaryGap: [0, 0.01]
          }],

          // Vertical axis
          yAxis: [{
            type: 'category',
            data: JSON.parse(`{!! json_encode($chart['label']) !!}`),
          }],

          // Add series
          series : [
            {
              name: 'เข้าเรียน',
              type: 'bar',
              itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
              data: JSON.parse(`{!! json_encode($chart['active']) !!}`),
            },
            {
              name: 'ไม่เข้าเรียน',
              type: 'bar',
              itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
              data: JSON.parse(`{!! json_encode($chart['inactive']) !!}`)
            }
          ]
        };

        // Apply options
        // ------------------------------

        myChart.setOption(chartOptions);

        // Resize chart
        // ------------------------------

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
    );
  });
  </script>

  {{-- คนเข้าเรียนผ่าน / ไม่ผ่าน / ไม่เข้าเรียน --}}
  <script>
  $(window).on("load", function(){

    // Set paths
    // ------------------------------

    require.config({
      paths: {
        echarts: '/stack-admin/app-assets/vendors/js/charts/echarts'
      }
    });


    // Configuration
    // ------------------------------

    require(
      [
        'echarts',
        'echarts/chart/bar',
        'echarts/chart/line'
      ],

      // Charts setup
      function (ec) {
        // Initialize chart
        // ------------------------------
        var myChart = ec.init(document.getElementById('stacked-bar'));

        // Chart Options
        // ------------------------------
        chartOptions = {

          // Setup grid
          grid: {
            x: 230,
            x2: 40,
            y: 45,
            y2: 25
          },

          // Add tooltip
          tooltip : {
            trigger: 'axis',
            axisPointer : {            // Axis indicator axis trigger effective
              type : 'shadow'        // The default is a straight line, optionally: 'line' | 'shadow'
            }
          },

          // Add Toolbook
          toolbox: {
            show : true,
            // orient: 'vertical',
            x: 'right',
            // y: 70,
            feature : {
              saveAsImage: {
                show: true,
                title: 'Save as image',
                name: Date.now(),
                lang: ['Save']
              }
            }
          },

          // Add legend
          legend: {
            data: ['ยังไม่เข้าเรียน', 'เข้าเรียน (ไม่ผ่าน)', 'เข้าเรียน (ผ่าน)']
          },

          // Add custom colors
          color: ['#F98E76', '#FDD835', '#16D39A'],

          // Horizontal axis
          xAxis: [{
            type: 'value',
          }],

          // Vertical axis
          yAxis: [{
            type: 'category',
            data: JSON.parse(`{!! json_encode($chart_active['label']) !!}`),
          }],

          // Add series
          series : [
            {
              name:'ยังไม่เข้าเรียน',
              type:'bar',
              stack: 'Total',
              itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
              data:JSON.parse(`{!! json_encode($chart_active['inactive']) !!}`)
            },
            {
              name:'เข้าเรียน (ไม่ผ่าน)',
              type:'bar',
              stack: 'Total',
              itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
              data: JSON.parse(`{!! json_encode($chart_active['not_pass']) !!}`)
            },
            {
              name:'เข้าเรียน (ผ่าน)',
              type:'bar',
              stack: 'Total',
              itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
              data: JSON.parse(`{!! json_encode($chart_active['pass']) !!}`)
            }
          ]
        };

        // Apply options
        // ------------------------------

        myChart.setOption(chartOptions);


        // Resize chart
        // ------------------------------

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
    );
  });
  </script>

  {{-- % คนไม่เข้าเรียน --}}
<script>
  $(window).on("load", function(){

    // Set paths
    // ------------------------------

    require.config({
      paths: {
        echarts: '/stack-admin/app-assets/vendors/js/charts/echarts'
      }
    });


    // Configuration
    // ------------------------------

    require(
      [
        'echarts',
        'echarts/chart/bar',
        'echarts/chart/line'
      ],

      // Charts setup
      function (ec) {
        // Initialize chart
        // ------------------------------
        var myChart = ec.init(document.getElementById('stacked-bar-inactive'));

        // Chart Options
        // ------------------------------
        chartOptions = {

          // Setup grid
          grid: {
            x: 230,
            x2: 40,
            y: 45,
            y2: 25
          },

          // Add tooltip
          tooltip : {
            trigger: 'axis',
            axisPointer : {            // Axis indicator axis trigger effective
              type : 'shadow'        // The default is a straight line, optionally: 'line' | 'shadow'
            }
          },

          // Add Toolbook
          toolbox: {
            show : true,
            // orient: 'vertical',
            x: 'right',
            // y: 70,
            feature : {
              saveAsImage: {
                show: true,
                title: 'Save as image',
                name: Date.now(),
                lang: ['Save']
              }
            }
          },

          // Add legend
          legend: {
            data: ['% คนไม่เข้าเรียน']
          },

          // Add custom colors
          color: ['#F98E76'],

          // Horizontal axis
          xAxis: [{
            type: 'value',
          }],

          // Vertical axis
          yAxis: [{
            type: 'category',
            data: JSON.parse(`{!! json_encode($chart_inactive['label']) !!}`)
          }],

          // Add series
          series : [
            {
              name:'% คนไม่เข้าเรียน',
              type:'bar',
              stack: 'Total',
              itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
              data: JSON.parse(`{!! json_encode($chart_inactive['total']) !!}`)
            }
          ]
        };

        // Apply options
        // ------------------------------

        myChart.setOption(chartOptions);


        // Resize chart
        // ------------------------------

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
    );
  });
  </script>
  <script>
   
   $("#btn-download").click(function () {
    var canvas = $(".basic-bar").toDataURL("image/jpeg");
    console.log(canvas)
    // var dataURL = canvas.toDataURL('image/jpeg');
    // $("#btn-download").attr("href", dataURL);
  });
  </script>
@endsection