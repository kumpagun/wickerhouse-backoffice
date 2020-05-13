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
  <div class="btn-group float-md-right mb-2 w-100" role="group" aria-label="Button group with nested dropdown">
    <form action="{{route('report_member_access_content_by_RO')}}" class=" w-100" method="POST">
      {{ csrf_field() }}
      <label class="text-left"> รอบอบรม</label>
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
      <div class="text-right">
        <a href="{{ route('report_member_access_content_by_RO', ['search_group'=>$search_group,'platform'=>'excel']) }}">
          <button class="btn btn-round btn-secondary my-1"><i class="ft-download mr-1"></i> Export</button>
        </a>
      </div>
      <div class="card">
        <div class="card-content collapse show">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="second-row">
                    <th class="align-middle text-center min-width-200">DeptName</th>
                    <th class="align-middle text-center">เข้าเรียน</th>
                    <th class="align-middle text-center">เข้าเรียน(ผ่าน)</th>
                    <th class="align-middle text-center">เข้าเรียน(ยังไม่ผ่าน)</th>
                    <th class="align-middle text-center">ยังไม่เข้าเรียน</th>
                    <th class="align-middle text-center">Total</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($datas))
                    @foreach($datas as $key => $value)
                    <tr>
                      <td class="">
                        @if(!empty($key)) {{ $key }} @else อื่นๆ @endif
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

  <div class="row align-items-stretch">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card">
        <div class="card-content collapse show">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="second-row">
                    <th class="align-middle text-center min-width-200">DeptName</th>
                    <th class="align-middle text-center">ยังไม่เข้าเรียน</th>
                    <th class="align-middle text-center">เข้าเรียน(ยังไม่ผ่าน)</th>
                    <th class="align-middle text-center">เข้าเรียน(ผ่าน)</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($datas))
                    @foreach($datas as $key => $value)
                    <tr>
                      <td class="">
                        @if(!empty($key)) {{ $key }} @else อื่นๆ @endif
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_inactive']))
                          {{ number_format($value['user_inactive']) }}
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
                        @if (!empty($value['user_active_passing_score']))
                          {{ number_format($value['user_active_passing_score']) }}
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
                    <td class="text-right"><strong>{{ number_format($data_total['user_inactive']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_active_not_passing_score']) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($data_total['user_active_passing_score']) }}</strong></td>
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

  <div class="row align-items-stretch">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card">
       
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr class="second-row">
                    <th class="align-middle text-center min-width-200">DeptName</th>
                    <th class="align-middle text-center">ยังไม่เข้าเรียน%</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($datas))
                    @foreach($datas as $key => $value)
                    <tr>
                      <td class="">
                        @if(!empty($key)) {{ $key }} @else อื่นๆ @endif
                      </td>
                      <td class="text-right">
                        @if (!empty($value['user_inactive']))
                          {{ number_format(($value['user_inactive']*100)/$data_total['user_inactive'],2) }}%
                        @else
                          {{ number_format(0,2) }}%
                        @endif
                      </td>
                    @endforeach
                  @else
                    <tr>
                      <td class="text-center" colspan="6">ไม่มีข้อมูล</td>
                    </tr>
                  @endif
                </tbody>
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
    <div class="col-md-8 col-sm-12">
      <div class="card" id="pie">
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
      $value_height = $i * 50;
      echo "<style>.height-".$value." { height: ".$value_height."px; } </style>";
    @endphp
  @endfor
@endsection

@section('script')
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
  {{-- <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/echarts/echarts.js') }}" type="text/javascript"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/echarts@4.6.0/dist/echarts.min.js"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN STACK JS-->
  <script src="{{ asset('stack-admin/app-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/core/app.js') }}" type="text/javascript"></script>
  <!-- END STACK JS-->
  <script>
    chart1()
    chart2()
    chart3()
    chart4()

    function chart1() {
      var myChart = echarts.init(document.getElementById('simple-pie-chart'));
      var seriesLabel = {
        normal: {
          fontSize: 14,
          color: '#000',
          textBorderColor: '#333',
          textBorderWidth: 1,
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
            orient: 'vertical',
            left: 'left',
            textStyle: {
              fontSize: 16
            },
            data: JSON.parse(`{!! json_encode($pie_chart['label']) !!}`),
          },
          color: ['#16D39A', '#F98E76'],
          series: [
            {
              name: 'พนักงาน',
              type: 'pie',
              radius: '70%',
              center: ['50%', '50%'],
              label: seriesLabel,
              minAngle: 30,
              data: JSON.parse(`{!! json_encode($pie_chart['outer_data']) !!}`),
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
      var myChart = echarts.init(document.getElementById('basic-bar'));
      var seriesLabel = {
        normal: {
          label : {
            fontSize: 14,
            show: true,
            color: '#000',
            textBorderColor: '#333',
            textBorderWidth: 1
          }
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
          data: ['เข้าเรียน', 'ไม่เข้าเรียน'],
          textStyle: {
            fontSize: 16
          },
        },
        // Add custom colors
        color: ['#16D39A', '#F98E76'],
        grid: {
          left: 100,
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
          axisLabel: {
            formatter: '{value}'
          }
        },
        yAxis: {
          type: 'category',
          inverse: true,
          data: JSON.parse(`{!! json_encode($chart['label']) !!}`),
        },
        series: [
          {
            name: 'เข้าเรียน',
            type: 'bar',
            itemStyle : seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($chart['active']) !!}`),
          },
          {
            name: 'ไม่เข้าเรียน',
            type: 'bar',
            itemStyle : seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($chart['inactive']) !!}`)
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

    function chart3() {
      var myChart = echarts.init(document.getElementById('stacked-bar'));
      var seriesLabel = {
        normal: {
          show: true,
          fontSize: 14,
          color: '#000',
          textBorderColor: '#333',
          textBorderWidth: 1
        }
      }
      option = {
        tooltip: {
          trigger: 'axis',
          axisPointer: {            // 坐标轴指示器，坐标轴触发有效
            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
          }
        },
        legend: {
          data: ['ยังไม่เข้าเรียน', 'เข้าเรียน (ยังไม่ผ่าน)', 'เข้าเรียน (ผ่าน)'],
          textStyle: {
            fontSize: 16
          },
        },
        // Add custom colors
        color: ['#F98E76', '#FDD835', '#16D39A'],
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
            return value.max;
          }
        },
        yAxis: {
          type: 'category',
          data: JSON.parse(`{!! json_encode($chart_active['label']) !!}`),
        },
        series: [
          {
            name: 'ยังไม่เข้าเรียน',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($chart_active['inactive']) !!}`)
          },
          {
            name: 'เข้าเรียน (ยังไม่ผ่าน)',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($chart_active['not_pass']) !!}`)
          },
          {
            name: 'เข้าเรียน (ผ่าน)',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 50,
            data: JSON.parse(`{!! json_encode($chart_active['pass']) !!}`)
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
      var myChart = echarts.init(document.getElementById('stacked-bar-inactive'));
      var seriesLabel = {
        normal: {
          show: true,
          fontSize: 14,
          color: '#000',
          textBorderColor: '#333',
          textBorderWidth: 1
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
          data: ['% คนไม่เข้าเรียน'],
          textStyle: {
            fontSize: 16
          },
        },
        // Add custom colors
        color: ['#F98E76'],
        grid: {
          left: 230,
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
          axisLabel: {
            formatter: '{value}'
          }
        },
        yAxis: {
          type: 'category',
          inverse: true,
          data: JSON.parse(`{!! json_encode($chart_inactive['label']) !!}`),
            label: seriesLabel
        },
        series: [
          {
            name:'% คนไม่เข้าเรียน',
            type:'bar',
            stack: 'Total',
            // itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
            data: JSON.parse(`{!! json_encode($chart_inactive['total']) !!}`),
            barMinHeight: 50,
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