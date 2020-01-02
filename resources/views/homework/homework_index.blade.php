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
  <div class="col-12 col-md-8 col-lg-8 col-xl-6">
    <div class="card">
      <div class="card-header pb-0">
        <h4 class="card-title">{{ $title }}</h4>
        <div class="btn-group float-right" role="group" aria-label="Button group with nested dropdown">
          @can('editor')
          <a class="btn btn-secondary" href="{{ route('homework_create') }}">Add Homework</a>
          @else
          <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>Add Homework</button>
          @endcan
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th>#</th>
              <th>Course</th>
            </tr>
            @if(count($datas)>0)
            @foreach ($datas as $item)
              <tr>
                <td><a href="{{ route('homework_create', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
                <td><a href="{{ route('homework_create', ['id' => $item->id]) }}">{{ CourseClass::get_name_course($item->course_id) }}</a></td>
              </tr>
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