@extends('layouts.app')

@php $title = strtoupper('ถาม-ตอบ'); @endphp

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
            <th class="text-center">ชื่อคอร์ส</th>
            <th class="text-center">จำนวนคนถาม / ตอบคำถาม</th>
          </tr>
          @if(count($datas)>0)
          @foreach ($datas as $item)
            @php $total_question = CourseClass::get_question_total($item->_id); @endphp
            @php $total_question_answer = CourseClass::get_question_answer_total($item->_id); @endphp
            @if(!empty($total_question)) 
            <tr>
              <td class="text-center"><a href="{{ route('question_answer_index',['question_id' => $item->_id]) }}">{{ $loop->iteration }}</a></td>
              <td><a href="{{ route('question_answer_index',['question_id' => $item->_id]) }}">{{ $item->title }}</a></td>
              <td class="text-center">{{ number_format($total_question).' / '.number_format($total_question_answer) }}</td>
            </tr>
            @else
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $item->title }}</td>
              <td class="text-center">{{ number_format($total_question).' / '.number_format($total_question_answer) }}</td>
            </tr>
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