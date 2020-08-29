@extends('layouts.app-nonav')

@section('style')
  <!-- BEGIN VENDOR CSS-->
  <link rel="stylesheet" type="text/css" href="{{ ('stack-admin/app-assets/vendors/css/forms/icheck/icheck.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ ('stack-admin/app-assets/vendors/css/forms/icheck/custom.css') }}">
  <!-- END VENDOR CSS-->
  <!-- BEGIN Page Level CSS-->
  <link rel="stylesheet" type="text/css" href="{{ ('stack-admin/app-assets/css/pages/login-register.css') }}">
  <!-- END Page Level CSS-->
@endsection

@section('script')
  <!-- BEGIN VENDOR JS-->
  <script src="{{ ('stack-admin/app-assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
  <!-- BEGIN VENDOR JS-->
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ ('stack-admin/app-assets/vendors/js/forms/icheck/icheck.min.js') }}" type="text/javascript"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN PAGE LEVEL JS-->
  <script src="{{ ('stack-admin/app-assets/js/scripts/forms/form-login-register.js') }}" type="text/javascript"></script>
  <!-- END PAGE LEVEL JS-->
@endsection

@section('content')
<section class="flexbox-container">
  <div class="col-12 d-flex align-items-center justify-content-center">
    <div class="col-lg-4 col-md-6 col-10 p-0"> {{-- box-shadow-1  --}}
      <div class="card border-grey border-lighten-3 m-0">
        <div class="card-header border-0">
          <div class="card-title text-center">
            <div class="p-1">
              WICKER HOUSE
            </div>
          </div>
          @if($errors->signin->has('signin'))
            <div class="alert bg-warning alert-icon-right alert-dismissible mb-0" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <strong>Warning!</strong> {{ $errors->signin->first('signin') }}
            </div>
          @endif
        </div>
        <div class="card-content">
          <div class="card-body pt-0">
            <form class="form-horizontal" method="POST" action="{{ route('auth_signin') }}">
              @csrf
              <fieldset class="form-group floating-label-form-group @if($errors->signin->has('username')) danger @endif">
                <label for="user-name">Your Username</label>
                <input name="username" type="text" class="form-control" id="user-name" placeholder="Your Username">
                @if($errors->signin->has('username'))
                  <span class="small" role="alert">
                    <p class="my-1">{{ $errors->signin->first('username') }}</p>
                  </span>
                @endif
              </fieldset>
              <fieldset class="form-group floating-label-form-group @if($errors->signin->has('password')) danger @endif">
                <label for="user-password">Enter Password</label>
                <input name="password" type="password" class="form-control" id="user-password" placeholder="Enter Password">
                @if($errors->signin->has('password'))
                  <span class="small" role="alert">
                    <p class="my-1">{{ $errors->signin->first('password') }}</p>
                  </p>
                @endif
              </fieldset>
              <button type="submit" class="btn btn-primary btn-block"><i class="ft-unlock"></i> Login</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
