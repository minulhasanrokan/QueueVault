<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Forgot Password | {{$system_data['system_title']}}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/system')}}/{{$system_data['system_icon']}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/adminlte.css')}}">
        <style type="text/css">
            .login-page{
                background:url('{{asset('uploads/system')}}/{{$system_data['system_bg_image']}}');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .input-error{
                display: inline-block;
                width:100%;
                color: red;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <p class="h4"><b>{{$system_data['system_name']}}</b></p>
                </div>
                <div class="card-body">
                    @if(isset($notification['message']) && $notification['create_status']==1)
                        <p class="text-center">{{$notification['message']}}</p>
                        <div class="input-group mb-3">
                            <a class="btn btn-primary btn-block" style="text-decoration:none; color:white;" href="{{route('admin.login')}}">Sign In</a>
                        </div>
                    @else
                        <p class="text-center">{{$notification['message']}}</p>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/adminlte.js')}}"></script>
    </body>
</html>