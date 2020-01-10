@extends('layouts.app')

@php $title = strtoupper('Permissions'); @endphp

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
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
  <a class="btn btn-secondary" href="{{ route('permissions_create') }}"><i class="ft-user"></i> Add {{ $title }}</a>
  @else
  <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>Add {{ $title }}</button>
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <tr>
            <th>#</th>
            <th>Permissions</th>
            <th>Status</th>
          </tr>
          @foreach ($datas as $item)
            <tr>
              <td><a href="{{ route('permissions_create', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
              <td><a href="{{ route('permissions_create', ['id' => $item->id]) }}">{{ $item->display_name }}</a></td>
              @if($item->status)
                <td><span class="badge badge badge-pill badge-success">Active</span></td>
              @else
                <td><span class="badge badge badge-pill badge-danger">Inactive</span></td>
              @endif
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>
@endsection