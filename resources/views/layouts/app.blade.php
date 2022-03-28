<!doctype html>
<html class="no-js " lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="keyword" content="LUNO, Bootstrap 5, ReactJs, Angular, Laravel, VueJs, ASP .Net, Admin Dashboard, Admin Theme, HRMS, Projects">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <link rel="icon" href="favicon.ico" type="image/x-icon">
      <link rel="stylesheet" href="{{ asset('/css/luno.style.min.css') }}">
      @stack('stylesheets')
   </head>
   <body class="layout-1" data-luno="theme-blue">
      <!-- start: sidebar -->
        @include('layouts.sidebar')
      <!-- start: body area -->
      <div class="wrapper">
         <!-- start: page header -->
         @include('layouts.header')
         <!-- start: page toolbar -->
         <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
            <div class="container-fluid">
                @yield('page-toolbar')
            </div>
         </div>
         <!-- start: page body -->
         <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-3">
             @yield('page-content')
             @include('layouts.modal-setting')
         </div>
         <!-- start: page footer -->
         @include('layouts.footer')
      </div>
      <!-- Jquery Core Js -->
      <script src="{{ asset('bundles/libscripts.bundle.js') }}"></script>
      <script src="{{ asset('bundles/apexcharts.bundle.js') }}"></script>
      <script src="{{ asset('bundles/daterangepicker.bundle.js') }}"></script>
      <script src="{{ asset('bundles/dataTables.bundle.js') }}"></script>
      @stack('scripts')
   </body>
</html>