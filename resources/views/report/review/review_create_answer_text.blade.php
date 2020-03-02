@extends('layouts.app')

@php $title = 'ข้อเสนอแนะ'; @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{!! $title !!}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('report_review_index') }}">ประเมินหลักสูตรหลังเรียน</a></li>
      <li class="breadcrumb-item"><a href="{{ route('report_review_create', ['training_id' => $training->id]) }}">{{ $training->title }}</a></li>
      <li class="breadcrumb-item active">{!! $title !!}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{!! $title !!}</h4>
        </div>
        <div class="card-content">
          <div class="card-body overflow-hidden"> 
            <div class="table-responsive">
              <table class="table table-hover table-sm">
                @foreach ($datas as $data)
                  <tr>
                    <td>{{ $data->review_text_answer }}</td>
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
          <div class="card-footer border-0 text-center">
            {{ $datas->links() }}
          </div>
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
    p {
      margin-bottom: 0;
    }
  </style>
@endsection