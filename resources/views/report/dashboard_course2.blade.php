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
    <form action="{{route('report_dashboard_course2')}}" class=" w-100" method="POST">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-12 col-md-6 col-xl-5">
          <label class="text-left"> รอบอบรม</label>
          <select id="search_group" name="search_group" class="form-control select2" onchange="handleChangeGroup()">
            @foreach($query_group as $key)
              <option value="{{$key->_id}}" @if( $search_group == (string)($key->_id)) selected @endif>{{ $key->title}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-md-6 col-xl-5">
          <label class="text-left"> Region</label>
          <select name="search_region[]" class="form-control select2" multiple>
            <option value="" @if(empty($search_region) || in_array('', $search_region)) selected @endif>ทั้งหมด</option>
            @foreach($arr_region as $key)
              <option value="{{$key}}" @if( in_array($key, $search_region) && !empty($search_region) ) selected @endif>{{ $key }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-xl-2">
          <label class="text-left"> &nbsp;</label>
          <button type="submit" class="btn btn-block btn-outline-secondary">
            ค้นหา
          </button>
        </div>
      </div>
    </form>
  </div>
  @endif
@endsection

@section('content')
  <div>
<!-- Column Stacked Chart -->
  <div class="row justify-content-center">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">สถานะผู้เข้าเรียนหลักสูตร {{ $training_title }} </h4>
        <p class="card-subtext">จำนวนพนักงาน {{ number_format($chart_active['total']) }} คน</p>
      </div>
      <div class="card-content collapse show">
        <div class="card-body">
          <div id="stacked-bar" class="height-500 echart-container"></div>
        </div>
      </div>
    </div>
  </div>
</div>
  </div>
  

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
    function handleChangeGroup() {
      $("[name='search_region[]']").empty()
      var training_id = $('#search_group').val()
      var url = "{{ route('get_region_from_training_id') }}"
      $.get(url, 
      {
        training_id: training_id
      },
      function(data, status){
        $("[name='search_region[]']").append($('<option>', {
          value: '',
          text: 'ทั้งหมด',
          selected: true
        }));
        data.forEach(function (ch) {
          if(ch!='') {
            $("[name='search_region[]']").append(
              $('<option> ', {
                value: ch,
                text: ch
              })
            )
          }
        })
      })
    }
  </script>
  <script>
    chart1()

    function chart1() {
      var myChart = echarts.init(document.getElementById('stacked-bar'));
      var seriesLabel = {
        normal: {
          show: true,
          fontSize: 14,
          color: '#000',
          textBorderColor: '#333',
          position: 'inside'
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
        color: ['#F98E76', '#FDD835', '#a5a5a5'],
        grid: {
          left: '100',
          right: '100',
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
          data: JSON.parse(`{!! json_encode($chart_active['label']) !!}`),
          axisLabel: {
            textStyle: {
              fontSize: 16
            },
            formatter: '{value}',
            rotate: 20
          }
        },
        yAxis: {
          type: 'value',
          max: function (value) {
            return value.max;
          }
        },
        series: [
          {
            name: 'ยังไม่เข้าเรียน',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 30,
            data: JSON.parse(`{!! json_encode($chart_active['inactive']) !!}`)
          },
          {
            name: 'เข้าเรียน (ยังไม่ผ่าน)',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 30,
            data: JSON.parse(`{!! json_encode($chart_active['not_pass']) !!}`)
          },
          {
            name: 'เข้าเรียน (ผ่าน)',
            type: 'bar',
            stack: 'total',
            label: seriesLabel,
            barMinHeight: 30,
            data: JSON.parse(`{!! json_encode($chart_active['pass']) !!}`)
          },
        ]
      };
      myChart.setOption(option);
      $(function () {
        // Resize chart on menu width change and window resize
        $(window).on('resize', resize);
        $(".menu-toggle").on('click', resize);
        $(document).ready(function() {
          resize
        })
        $(document).ready(function(){
          resize()
        });

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