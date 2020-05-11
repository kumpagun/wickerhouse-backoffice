@extends('layouts.app')

@php $title = $training->title; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('report_review_training_index') }}">ประเมินหลักสูตรหลังเรียน</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content-header-right')
  <div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
    <a href="{{ route('report_review_create', ['training_id'=>$training->_id,'platform'=>'excel']) }}">
      <button class="btn btn-round btn-secondary"><i class="ft-download mr-1"></i> Export</button>
    </a>
  </div>
@endsection


@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      @foreach ($review_group as $group)
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
          </div>
          <div class="card-content">
            <div class="card-body overflow-hidden"> 
              <div class="table-responsive">
                <table class="table table-hover table-sm">
                  @foreach ($reviews as $review)
                    @if($review->type=='choice')
                      @if($group->_id == $review->review_group_id)
                        <thead>
                          <tr>
                            <th class="text-left">{!! $review->title !!}</th>
                            @foreach ($data_choice[$review->_id] as $choice)
                              <th class="text-center content-table td-width">{{ $choice }}</th>
                              <th class="text-center content-table td-width">%{{ $choice }}</th>
                            @endforeach
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($data_question[$review->_id] as $index_question => $value_question)
                          <tr>
                            <td class="text-left">{!! $value_question !!}</td>
                            @foreach ($data_choice[$review->_id] as $index => $value)
                              @if(!empty($datas_report[$review->_id]['choice'][$index_question][$data_choice[$review->_id][$index]]))
                                <td class="text-center td-width">{{ $datas_report[$review->_id]['choice'][$index_question][$data_choice[$review->_id][$index]] }}</td>
                                @php
                                  $percent = ($datas_report[$review->_id]['choice'][$index_question][$data_choice[$review->_id][$index]]/$datas_report[$review->_id]['choice_total'][$index_question]) * 100;
                                @endphp
                                <td class="text-center td-width">
                                  {{ number_format($percent,2) }} %
                                </td>
                              @else
                                <td class="text-center td-width">0</td>
                                <td class="text-center td-width">0 %</td>
                              @endif
                            @endforeach
                          </tr>
                        @endforeach
                        </tbody>
                      @endif
                    @else
                      @if($group->_id == $review->review_group_id)
                        <tr>
                          <th class="text-left content-table" colspan="99">{!! $review->title !!}</th>
                        </tr>
                        @if(!empty($datas_report[$review->_id]['text']))
                          @foreach ($datas_report[$review->_id]['text'] as $value)
                            <tr>
                              <td class="text-left" colspan="99">{{ $value }}</td>
                            </tr>
                          @endforeach
                          @if($count_report[$review->_id]==10)
                            <tr>
                              <td class="text-center" colspan="99"><a href="{{ route('report_review_training_create_answer_text', ['review_id' => $review->_id]) }}">ดูเพิ่มเติม</a></td>
                            </tr>
                          @endif
                        @endif
                      @endif
                    @endif
                  @endforeach
                </table>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
@section('style')
  <style>
    .no-table{
      width: 10%;
    }
    .content-table{
      width: 40%;
    }
    .td-width {
      width: 10%;
    }
    p {
      margin-bottom: 0;
    }
  </style>
@endsection