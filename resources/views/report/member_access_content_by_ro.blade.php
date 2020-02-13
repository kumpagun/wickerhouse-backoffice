@extends('layouts.app')

@php $title = strtoupper('Dashboeard'); @endphp

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
<!-- Basic Tables start -->
<div class="row align-items-stretch">
  <div class="col-12">
    <div class="card">
      <div class="card-content collapse show">
        <div class="card-body">
          <div class="row align-items-baseline">
            <label class="col-12 col-md-8 text-right"> Training List</label>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <form action="{{route('index')}}" method="POST">
                  {{ csrf_field() }}
                  <select name="search_group" class="form-control select2" onchange="this.form.submit()">
                    @foreach($query_group as $key)
                      <option value="{{$key->_id}}" @if( $search_group == (string)($key->_id)) selected @endif>{{ $key->title}}</option>
                    @endforeach
                  </select>
                </form>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                {{-- <tr class="">
                  <th colspan="1" class="align-middle text-center head-table">Count of Status </th>
                  <th colspan="5" class="align-middle text-center">Status</th>
                </tr> --}}
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
          <canvas id="simple-pie-chart" height="400"></canvas>
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
          <canvas id="column-chart" height="400"></canvas>
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
          <canvas id="column-stacked" height="400"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Column Chart inactive -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">สถานะผู้ไม่เข้าเรียนหลักสูตร {{ $training_title }} ณ วันที่ {{ $last_update }}</h4>
      </div>
      <div class="card-content collapse show">
        <div class="card-body">
          <canvas id="column-chart-inactive" height="400"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
  <script src="{{asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js')}}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
  <!-- BEGIN VENDOR JS-->
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN STACK JS-->
  <script src="{{ asset('stack-admin/app-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/core/app.js') }}" type="text/javascript"></script>
  <!-- END STACK JS-->

  <script>
  $(window).on("load", function(){

    //Get the context of the Chart canvas element we want to select
    var ctx = $("#simple-pie-chart");

    // Chart Options
    var chartOptions = {
      responsive: true,
      maintainAspectRatio: false,
      responsiveAnimationDuration:500,
    };

    // Chart Data
    var chartData = {
      labels: JSON.parse(`{!! json_encode($pie_chart['label']) !!}`),
      datasets: [{
        label: "My First dataset",
        data: JSON.parse(`{!! json_encode($pie_chart['total']) !!}`),
        backgroundColor: ['#00A5A8', '#626E82', '#FF7D4D','#FF4558', '#16D39A'],
      }]
    };

    var config = {
      type: 'pie',

      // Chart Options
      options : chartOptions,

      data : chartData
    };

    // Create the chart
    var pieSimpleChart = new Chart(ctx, config);
  });
  </script>

  {{-- คนเข้าเรียน ไม่เข้าเรียน --}}
  <script>
    $(window).on("load", function(){

      //Get the context of the Chart canvas element we want to select
      var ctx = $("#column-chart");

      // Chart Options
      var chartOptions = {
        // Elements options apply to all of the options unless overridden in a dataset
        // In this case, we are setting the border of each bar to be 2px wide and green
        elements: {
          rectangle: {
            borderWidth: 2,
            borderColor: 'rgb(0, 255, 0)',
            borderSkipped: 'bottom'
          }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        legend: {
          position: 'top',
        },
        scales: {
          xAxes: [{
            display: true,
            gridLines: {
              color: "#f3f3f3",
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            }
          }],
          yAxes: [{
            display: true,
            gridLines: {
              color: "#f3f3f3",
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            }
          }]
        },
        title: {
          display: true,
          text: 'สถานะผู้เข้าเรียน'
        }
      };

      // Chart Data
      var chartData = {
        labels: JSON.parse(`{!! json_encode($chart['label']) !!}`),
        datasets: [{
          label: "เข้าเรียน",
          data: JSON.parse(`{!! json_encode($chart['active']) !!}`),
          backgroundColor: "#16D39A",
          hoverBackgroundColor: "#16D39A",
          borderColor: "transparent"
        }, {
          label: "ยังไม่เข้าเรียน",
          data: JSON.parse(`{!! json_encode($chart['inactive']) !!}`),
          backgroundColor: "#F98E76",
          hoverBackgroundColor: "#F98E76",
          borderColor: "transparent"
        }]
      };

      var config = {
        // Chart type
        type: 'bar',
        // Chart Options
        options : chartOptions,
        // Chart Data
        data : chartData
      };

      // Create the chart
      var lineChart = new Chart(ctx, config);
    });
  </script>

  {{-- คนเข้าเรียนผ่าน / ไม่ผ่าน / ไม่เข้าเรียน --}}
  <script>
    $(window).on("load", function(){

    // Get the context of the Chart canvas element we want to select
    var ctx = $("#column-stacked");

    // Chart Options
    var chartOptions = {
      title:{
        display:false,
        text:"Chart.js Column Chart - Stacked"
      },
      tooltips: {
        mode: 'label'
      },
      responsive: true,
      maintainAspectRatio: false,
      responsiveAnimationDuration:500,
      scales: {
        xAxes: [{
          stacked: true,
          display: true,
          gridLines: {
            color: "#f3f3f3",
            drawTicks: false,
          },
          scaleLabel: {
            display: true,
          }
        }],
        yAxes: [{
          stacked: true,
          display: true,
          gridLines: {
            color: "#f3f3f3",
            drawTicks: false,
          },
          scaleLabel: {
            display: true,
          }
        }]
      }
    };

    // Chart Data
    var chartData = {
      labels: JSON.parse(`{!! json_encode($chart_active['label']) !!}`),
      datasets: [
        {
          label: "ยังไม่เข้าเรียน",
          data: JSON.parse(`{!! json_encode($chart_active['inactive']) !!}`),
          backgroundColor: "#F98E76",
          hoverBackgroundColor: "#F98E76",
          borderColor: "transparent"
        },
        {
          label: "เข้าเรียน (ไม่ผ่าน)",
          data: JSON.parse(`{!! json_encode($chart_active['not_pass']) !!}`),
          backgroundColor: "#FDD835",
          hoverBackgroundColor: "#FDD835",
          borderColor: "transparent"
        },
        {
          label: "เข้าเรียน (ผ่าน)",
          data: JSON.parse(`{!! json_encode($chart_active['pass']) !!}`),
          backgroundColor: "#16D39A",
          hoverBackgroundColor: "#16D39A",
          borderColor: "transparent"
        }, 
      ]
    };

    var config = {
        type: 'bar',

        // Chart Options
        options : chartOptions,

        data : chartData
    };

    // Create the chart
    var lineChart = new Chart(ctx, config);
  })
  </script>

  {{-- % คนไม่เข้าเรียน --}}
  <script>
    $(window).on("load", function(){

      //Get the context of the Chart canvas element we want to select
      var ctx = $("#column-chart-inactive");

      // Chart Options
      var chartOptions = {
        // Elements options apply to all of the options unless overridden in a dataset
        // In this case, we are setting the border of each bar to be 2px wide and green
        elements: {
          rectangle: {
            borderWidth: 2,
            borderColor: 'rgb(0, 255, 0)',
            borderSkipped: 'bottom'
          }
        },
        responsive: true,
        maintainAspectRatio: false,
        responsiveAnimationDuration:500,
        legend: {
          position: 'top',
        },
        scales: {
          xAxes: [{
            display: true,
            gridLines: {
              color: "#f3f3f3",
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            }
          }],
          yAxes: [{
            display: true,
            gridLines: {
              color: "#f3f3f3",
              drawTicks: false,
            },
            scaleLabel: {
              display: true,
            }
          }]
        },
        title: {
          display: true,
          text: 'สถานะผู้ไม่เข้าเรียน'
        }
      };

      // Chart Data
      var chartData = {
        labels: JSON.parse(`{!! json_encode($chart_inactive['label']) !!}`),
        datasets: [
          {
            label: "% คนไม่เข้าเรียน",
            data: JSON.parse(`{!! json_encode($chart_inactive['total']) !!}`),
            backgroundColor: "#F98E76",
            hoverBackgroundColor: "#F98E76",
            borderColor: "transparent"
          }
        ]
      };

      var config = {
        // Chart type
        type: 'bar',
        // Chart Options
        options : chartOptions,
        // Chart Data
        data : chartData
      };

      // Create the chart
      var lineChart = new Chart(ctx, config);
    });
  </script>
@endsection