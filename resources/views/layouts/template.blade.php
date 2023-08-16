<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

<link rel="icon" type="image/png" href="{{ asset('dist/img/systemAIRCoDeLogo.png') }}">

<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- fullCalendar -->
<link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.css') }}">

<link rel="stylesheet" href="{{ asset('plugins/simplemde/simplemde.min.css') }}">


<style>
        /* Change the dark-themed sidebar background color to blue */
        .sidebar-dark-primary {
            background-color: #022A44 !important;
        }

        /* If you want to change the light-themed sidebar as well, use this */
        .sidebar-light-primary {
            background-color: #022A44 !important;
        }
        .card {
            background-color: #ffffff !important;
        }
        .content-wrapper {
            background-color: #f3f4f6 !important;
        }

    </style>
</head>


<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

  @include('layouts.topnav')

  @include('layouts.sidebar')

  @yield('content')



<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('dist/js/demo.js') }}"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>

<!-- datatables-->
<script src="{{ asset('datatables/jquery.min.js') }}"></script>
<script src="{{ asset('datatables/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('datatables/datatables.min.js') }}"></script>
<script src="{{ asset('datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('datatables/datatables-demo.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>


</body>
</html>
