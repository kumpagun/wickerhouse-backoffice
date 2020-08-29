@extends('layouts.app')

@php $title = strtoupper('List user'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
@endsection

@section('content-header-right')
<div class="btn-group float-md-right" role="group" aria-label="Button group with nested dropdown">
  @can('editor')
  <a class="btn btn-secondary" href="{{ route('users_register') }}"><i class="ft-user"></i> Add user</a>
  @else
  <button  type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'><i class="ft-user"></i> Add user</button>
  @endcan
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">User list</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Role</th>
            <th>Permissions</th>
            <th>Status</th>
          </tr>
          @foreach ($datas as $item)
            <tr>
              <td><a href="{{ route('users_detail', ['id' => $item->id]) }}">{{ $loop->iteration }}</a></td>
              <td><a href="{{ route('users_detail', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
              <td>
                <a class="@if($item->type=='jasmine') color-2 @endif" href="{{ route('users_detail', ['id' => $item->id]) }}">
                  @if($item->type!='jasmine') 
                    Normal
                  @else 
                    <span class="color-2">Jasmine</span>
                  @endif
                </a>
              </td>
              <td><a href="{{ route('users_detail', ['id' => $item->id]) }}">{{ User::get_name_role($item->_id) }}</a></td>
              <td><a href="{{ route('users_detail', ['id' => $item->id]) }}">{{ User::get_name_permission($item->_id) }}</a></td>
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