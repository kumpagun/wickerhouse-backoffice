@extends('layouts.app')

@php $title = strtoupper('ตรวจแบบฝึกหัดหลังเรียน'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">ตรวจแบบฝึกหัดหลังเรียน</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <tr>
            <th class="text-center table-no">#</th>
            <th class="text-center">รอบอบรม</th>
            <th class="text-center">วันที่เริ่ม - สิ้นสุด</th>
            <th class="text-center">จำนวนคน / ตอบคำถาม</th>
          </tr>
          @if(count($datas)>0)
          @php $number = 1; @endphp
          @foreach ($datas as $item)
            @if(CourseClass::get_have_homework($item->course_id))
              @php $total_answer = CourseClass::get_homework_answer_total($item->_id); @endphp
              @if(!empty($total_answer)) 
              <tr>
                <td class="text-center"><a href="{{ route('homework_answer_index',['training_id' => $item->_id]) }}">{{ $number }}</a></td>
                <td><a href="{{ route('homework_answer_index',['training_id' => $item->_id]) }}">{{ $item->title }}</a></td>
                <td class="text-center">{{ FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($item->published_at) }} - {{ FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($item->expired_at) }}</td>
                <td class="text-center">{{ number_format($item->total_employee) }} / {{ number_format($total_answer) }}</td>
              </tr>
              @else
              <tr>
                <td class="text-center">{{ $number }}</td>
                <td>{{ $item->title }}</td>
                <td class="text-center">{{ FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($item->published_at) }} - {{ FuncClass::utc_to_carbon_format_time_zone_bkk_in_format($item->expired_at) }}</td>
                <td class="text-center">{{ number_format($item->total_employee) }} / {{ number_format($total_answer) }}</td>
              </tr>
              @endif
              @php $number++; @endphp
            @endif
          @endforeach
          @else
            <tr><td colspan="99" class="text-center">ไม่มีข้อมูล</td></tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>
@endsection