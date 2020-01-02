@extends('layouts.app')

@php $title = strtoupper('add user'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('users_index') }}">Users</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row align-items-center justify-content-center">
  <div class="col-md-6 col-10">
    <div class="card px-1 py-1 m-0">
      <div class="card-header border-0 pb-0">
        <div class="card-title text-center">
          <img src="{{ asset('stack-admin/app-assets/images/logo/stack-logo-dark.png') }}" alt="branding logo">
        </div>
        <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
          <span>USER INFO</span>
        </h6>
      </div>
      <div class="card-content">
        <div class="card-body pt-0">
          <form class="form-horizontal" action="{{ route('users_store') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('name')) danger @endif">
              <label for="user-name">Name</label>
              <input type="text" name="name" class="form-control" id="user-name" value="{{ old('name', $data->name) }}" placeholder="Name">
              @if($errors->register->has('name'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('name') }}</p>
                </span>
              @endif
            </fieldset>
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('username')) danger @endif">
              <label for="user-name">Username (ใช้สำหรับเข้าสู่ระบบ)</label>
              <input type="text" name="username" class="form-control" id="user-name" placeholder="Username"
                value="{{ old('username', $data->username) }}" 
                @if(!empty($data->id)) disabled @endif
              >
              @if($errors->register->has('username'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('username') }}</p>
                </span>
              @endif
            </fieldset>
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('email')) danger @endif">
              <label for="user-email">Your Email Address</label>
              <input type="email" name="email" class="form-control" id="user-email" placeholder="Your Email Address"
                value="{{ old('email', $data->email) }}" 
                @if(!empty($data->id)) disabled @endif
              >
              @if($errors->register->has('email'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('email') }}</p>
                </span>
              @endif
            </fieldset>
            @if(empty($data->id))
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('password')) danger @endif">
              <label for="user-password">Enter Password</label>
              <input type="password" name="password" class="form-control" id="user-password" placeholder="Enter Password">
              @if($errors->register->has('password'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('password') }}</p>
                </span>
              @endif
            </fieldset>
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('password_confirmation')) danger @endif">
              <label for="user-password">Password Confirmation</label>
              <input type="password" name="password_confirmation" class="form-control" id="user-password" placeholder="Enter Password">
              @if($errors->register->has('password_confirmation'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('password_confirmation') }}</p>
                </span>
              @endif
            </fieldset>
            @else
              <fieldset class="form-group floating-label-form-group @if($errors->register->has('password_confirmation')) danger @endif">
                <label for="user-password">Password</label>
                <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#xSmall"><i class="fa fa-user-o"></i> Reset Password</button>
              </fieldset>
            @endif
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('role')) danger @endif">
              <label for="user-role">Roles</label>
              <select class="select2 form-control" name="role[]" multiple="multiple">
                <optgroup label="Roles">
                  @foreach (User::get_roles() as $item)
                    <option value={{ $item->name }} 
                      @if(!empty($data->role_ids) && in_array($item->id,$data->role_ids)) selected  @endif
                    >{{ $item->display_name }}</option>
                  @endforeach
                </optgroup>
              </select>
              @if($errors->register->has('role'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('role') }}</p>
                </span>
              @endif
            </fieldset>
            <fieldset class="form-group floating-label-form-group @if($errors->register->has('permission')) danger @endif">
              <label for="user-permission">Permissions</label>
              <select class="select2 form-control" name="permission[]" multiple="multiple">
                <optgroup label="permission">
                  @foreach (User::get_permissions() as $item)
                    <option value={{ $item->name }} 
                      @if(!empty($data->permission_ids) && in_array($item->id,$data->permission_ids)) selected  @endif
                    >{{ $item->display_name }}</option>
                  @endforeach
                </optgroup>
              </select>
              @if($errors->register->has('permission'))
                <span class="small" role="alert">
                  <p class="mb-0">{{ $errors->register->first('permission') }}</p>
                </span>
              @endif
            </fieldset>
            <div class="row skin skin-square mb-2">
              <div class="col-md-6 col-sm-12">
                <label for="user-status">Status</label>
                <fieldset>
                  <input type="radio" name="status" id="input-radio-active" value=1 @if($data->status==1) checked @endif>
                  <label for="input-radio-active">Active</label>
                </fieldset>
                <fieldset>
                  <input type="radio" name="status" id="input-radio-inactive" value=0 @if($data->status==0) checked @endif>
                  <label for="input-radio-inactive">Inactive</label>
                </fieldset>
              </div>
            </div>
            @can('editor')
            <button type="submit" class="btn btn-primary btn-block">@if(!empty($data->id)) UPDATE @else REGISTER @endif </button>
            @else
            <button  type="button" class="btn btn-danger btn-block" data-toggle="tooltip" data-placement="bottom" title='Required "Editor" Permission'>NOT ALLOW</button>
            @endcan
          </form>
        </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="xSmall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel20"aria-hidden="true">
  <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel20">Reset Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('users_resetpassword') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $data->id }}">
        <div class="modal-body">
          <fieldset class="form-group floating-label-form-group @if($errors->resetpassword->has('password')) danger @endif">
            <label for="user-password">Enter Password</label>
            <input type="password" name="password" class="form-control" id="user-password" placeholder="Enter Password">
            @if($errors->resetpassword->has('password'))
              <span class="small" role="alert">
                <p class="mb-0">{{ $errors->resetpassword->first('password') }}</p>
              </span>
            @endif
          </fieldset>
          <fieldset class="form-group floating-label-form-group @if($errors->resetpassword->has('password_confirmation')) danger @endif">
            <label for="user-password">Password Confirmation</label>
            <input type="password" name="password_confirmation" class="form-control" id="user-password" placeholder="Enter Password">
            @if($errors->resetpassword->has('password_confirmation'))
              <span class="small" role="alert">
                <p class="mb-0">{{ $errors->resetpassword->first('password_confirmation') }}</p>
              </span>
            @endif
          </fieldset>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-outline-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/plugins/forms/checkboxes-radios.css') }}">
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/checkbox-radio.js') }}" type="text/javascript"></script>
  <script>
    var error = {{ $errors->resetpassword->has('password') }}
    if(error) $('#xSmall').modal()

    var success = {{ session()->has('success') }}
    if(success) alert('success')
  </script>
@endsection
