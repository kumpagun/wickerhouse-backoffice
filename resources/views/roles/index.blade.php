@extends('layouts.app')

@php $title = strtoupper('Roles'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Roles</li>
    </ol>
  </div>
</div>
@endsection

@section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
  <a class="btn btn-secondary" href="{{ route('roles_create') }}">Add Role</a>
  @else
  <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>Add Role</button>
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h4 class="card-title">{{ $title }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <td>#</td>
              <td>Role</td>
              <td>Status</td>
            </tr>
            @foreach ($datas as $item)
              <tr>
                <td><a href="{{ route('roles_create', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
                <td><a href="{{ route('roles_create', ['id' => $item->id]) }}">{{ $item->display_name }}</a></td>
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
</div>
@endsection