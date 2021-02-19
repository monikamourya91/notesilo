<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin-assets/css/custom.css') }}">

</head>
<body class="bg-white">
    @yield('content')

    <script type="application/javascript" src="{{ asset('public/admin-assets/js/jquery.min.js') }}"></script>
    <script type="application/javascript" src="{{ asset('public/admin-assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
