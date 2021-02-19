<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--<title>{{ config('app.name', 'Admin | ExtensionBuyer') }}</title>-->
    <title>Reseller  <?php echo Config::get('constants.copy_right'); ?></title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->

    <!-- ==== Document Meta ==== -->
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- ==== Favicon ==== -->
    <link rel="icon" href="{{ asset('admin-assets/favicon.png') }}" type="{{ asset('admin/image/png') }}">

    <!-- ==== Google Font ==== -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700%7CMontserrat:400,500">
    
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/perfect-scrollbar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/morris.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/jquery-jvectormap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/horizontal-timeline.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/ion.rangeSlider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/ion.rangeSlider.skinFlat.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/datatables.min.css') }}">

    
    <!-- Page Level Stylesheets -->
</head>


<body >
   <!--  <div id="app">-->
    @yield('content') 
    <!-- </div> -->
</body>
</html>
