<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard | {{$system_data['system_title']}}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/system')}}/{{$system_data['system_icon']}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/adminlte.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/summernote/summernote-bs4.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui/jquery-ui.min.css')}}">

        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/adminlte.js')}}"></script>
        <script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>

        <script src="{{asset('assets/js/common.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/js/bootstrap-datepicker.js')}}"></script>
        <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

        <link rel="stylesheet" href="{{asset('assets/css/toastr.css')}}">
        <script src="{{asset('assets/js/toastr.min.js')}}"></script>

        <link rel="stylesheet" href="{{asset('assets/css/bootstrap-select.min.css')}}">
        <script src="{{asset('assets/js/bootstrap-select.min.js')}}"></script>
        <script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
    </head>
    <body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <section id="loading">
            <div id="loading-content"></div>
        </section>
        <div class="wrapper">
            @include('admin.includes.header')
            @include('admin.includes.sidebar')
            <div class="content-wrapper">
                <section class="content" id="page_content">
                    @yield('content')
                </section>
            </div>
            @include('admin.includes.footer')
        </div>
    </body>
</html>