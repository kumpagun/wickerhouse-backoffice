@extends('layouts.app')

@php $title = strtoupper('ประเมินหลักสูตรหลังเรียน'); @endphp

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
    <div class="col-12 col-md-10 col-xl-8">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <tr>
              <th class="text-center no-table">#</th>
              <th class="text-center content-table">รอบการอบรม</th>
            </tr>
            @if (!empty($datas) && count($datas) > 0)
              @foreach ($datas as $item)
                <tr>
                  <td class="text-center"><a href="{{ route('report_review_training_create', ['training_id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
                  <td class="text-left"><a href="{{ route('report_review_training_create', ['training_id' => $item->id]) }}">{{ $item->title }}</a></td>
                </tr>
              @endforeach
            @else
              <tr>
                <td class="text-center" colspan="99">
                  {{"ไม่มีข้อมูล"}}
                </td>
              </tr>   
            @endif
          </table>
        </div>
      </div>
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
  </style>
@endsection