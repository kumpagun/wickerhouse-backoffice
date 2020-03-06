<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
  <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
  <meta name="author" content="PIXINVENT">
  <title>Jas Online Learning</title>
  <link rel="apple-touch-icon" href="{{ asset('stack-admin/app-assets/images/ico/apple-icon-120.png') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
  rel="stylesheet">
  <!-- BEGIN: Vendor CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/vendors.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/extensions/unslider.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/weather-icons/climacons.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/fonts/meteocons/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/charts/morris.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
  <!-- END: Vendor CSS-->

  <!-- BEGIN: Theme CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/bootstrap-extended.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/colors.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/components.css') }}">
  <!-- END: Theme CSS-->

  <!-- BEGIN: Page CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/core/colors/palette-gradient.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/fonts/simple-line-icons/style.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/pages/timeline.css') }}">
  <!-- END: Page CSS-->

  <!-- BEGIN: Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/assets/css/style.css') }}">
  <!-- END: Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  @yield('style')
  @stack('style')
  <!-- END Custom CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/vendors/css/forms/selects/select2.min.css') }}">
  
  <!-- Include Quill stylesheet -->
  <link rel="stylesheet" type="text/css" href="{{asset('css/quill.snow.css')}}">
</head>
<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
  @include('include.navbar')
  @include('include.sidenav')

  

  <div class="app-content content">
    <div class="content-wrapper">
      <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
          @yield('content-header-left')
        </div>
        <div class="content-header-right col-md-6 col-12">
          @yield('content-header-right')
        </div>
      </div>
      <div class="content-body">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- BEGIN VENDOR JS-->
  <script src="{{ asset('stack-admin/app-assets/vendors/js/vendors.min.js') }}" type="text/javascript"></script>
  <!-- BEGIN VENDOR JS-->
  <!-- BEGIN PAGE VENDOR JS-->
  <script src="{{ asset('stack-admin/app-assets/vendors/js/extensions/jquery.knob.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/extensions/knob.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/raphael-min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/morris.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/data/jvector/visitor-data.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/charts/jquery.sparkline.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/extensions/unslider-min.js') }}" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/css/core/colors/palette-climacon.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('stack-admin/app-assets/fonts/simple-line-icons/style.min.css') }}">
  <script src="{{ asset('stack-admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/extensions/polyfill.min.js') }}"></script>
  <!-- END PAGE VENDOR JS-->
  <!-- BEGIN STACK JS-->
  <script src="{{ asset('stack-admin/app-assets/js/core/app-menu.js') }}"></script>
  <script src="{{ asset('stack-admin/app-assets/js/core/app.js') }}"></script>

  <script src="{{ asset('stack-admin/app-assets/js/scripts/customizer.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('stack-admin/app-assets/js/scripts/forms/select/form-select2.js') }}" type="text/javascript"></script>
  <!-- END STACK JS-->
  <!-- Include the Quill library -->
  <script src="{{ asset('js/quill.js')}}" type="text/javascript"></script>
  <!-- BEGIN PAGE LEVEL JS-->
  <script>
    $(document).ready(function() {
      var url = window.location; 
      var element = $('ul.navigation a').filter(function() {
      return this.href == url || url.href.indexOf(this.href) == 0; }).parent().addClass('active');
      if (element.is('li')) { 
        element.addClass('active').parent().parent('li').addClass('active')
      }
    });
  </script>
  @yield('script')
  @stack('script')
  <!-- END PAGE LEVEL JS-->
</body>
</html>
