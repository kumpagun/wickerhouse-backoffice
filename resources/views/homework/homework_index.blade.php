@extends('layouts.app')

@php $title = strtoupper('Homework'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Homework</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-md-10 col-xl-8">
    <div class="card">
      <div class="card-header pb-0">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <tr>
              <th class="text-center table-no">#</th>
              <th class="text-center">Training Name</th>
              <th class="text-center">จำนวนคน / ตอบคำถาม</th>
            </tr>
            @if(count($datas)>0)
            @foreach ($datas as $item)
              @if(CourseClass::get_have_homework($item->course_id))
                @php $total_answer = CourseClass::get_homework_answer_total($item->_id); @endphp
                @if(!empty($total_answer)) 
                <tr>
                  <td><a href="{{ route('homework_answer_index',['training_id' => $item->_id]) }}">{{ $loop->iteration }}</a></td>
                  <td><a href="{{ route('homework_answer_index',['training_id' => $item->_id]) }}">{{ $item->title }}</a></td>
                  <td class="text-center">{{ number_format($item->total_employee) }} / {{ number_format($total_answer) }}</td>
                </tr>
                @else
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->title }}</td>
                  <td class="text-center">{{ number_format($item->total_employee) }} / {{ number_format($total_answer) }}</td>
                </tr>
                @endif
              @endif
            @endforeach
            @else
              <tr><td colspan="2" class="text-center">ไม่มีข้อมูล</td></tr>
            @endif
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection