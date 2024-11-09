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
                    @if(isset($notification['message']))
                        <h5 class="text-center">{{$notification['message']}}</h5>
                    @endif
                    @if(isset($notification['message']) && isset($notification['create_status']) && $notification['create_status']==2)
                        <a class="btn btn-primary btn-block" style="text-decoration: none; color:white;" href="{{route('admin.login')}}">Login Your Account</a>
                    @endif
                    @if(isset($notification['message']) && isset($notification['create_status']) && $notification['create_status']==0)
                    <p class="login-box-msg">Reset Your Password</p>
                    <form action="{{route('reset.password',$notification['user_id'])}}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="hidden" name="hidden_token" id="hidden_token" class="form-control" value="{{$notification['user_id']}}" required>
                            <input type="number" name="token" id="token" tabindex="1" class="form-control" placeholder="Insert Token" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mobile"></span>
                                </div>
                            </div>
                            <div class="input-error" id="token_error" style="display: inline-block; width:100%; color: red;">
                                @error('token'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" minlength="{{$system_data['password_min_length']}}" maxlength="{{$system_data['password_max_length']}}" tabindex="2" id="password" class="form-control" placeholder="New Password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="input-error" id="password_error" style="display: inline-block; width:100%; color: red;">
                                @error('password'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="confirm_password" minlength="{{$system_data['password_min_length']}}" maxlength="{{$system_data['password_max_length']}}" tabindex="3" id="confirm_password" class="form-control" placeholder="Retype New password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="input-error" id="confirm_password_error" style="display: inline-block; width:100%; color: red;">
                                @error('confirm_password'){{ $message }}@enderror
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="input-group mb-3">
                            <button type="submit" onclick="return form_validation();" class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                        <div class="col-12">
                            <p class="mb-1 text-center">
                                <a href="{{route('admin.login')}}">Back To Login</a>
                            </p>
                        </div>
                    </form>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/adminlte.js')}}"></script>
        <script type="text/javascript">

            $('#token').focus();
            
            function form_validation(){

                var password_min_length =  {{$system_data['password_min_length']}};
                var password_max_length =  {{$system_data['password_max_length']}};

                var token = $("#token").val();
                var confirm_password = $("#confirm_password").val();
                var password = $("#password").val();

                var token_element = document.getElementById('token');
                var password_element = document.getElementById('password');
                var confirm_password_element = document.getElementById('confirm_password');

                var pattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{<?php echo $system_data['password_max_length'];?>,}$/;

                if(token==''){

                    token_element.style.border = '1px solid red';

                    $("#token_error").html('Token Is Requrired');

                    return false;
                }
                else{

                    $("#token_error").html('');
                    token_element.style.border = '1px solid #ced4da';
                }

                if (!pattern.test(password)){

                    password_element.style.border = '1px solid red';

                    $("#password_error").html('Password  Must Contain At Least One letter, One digit, And Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

                    return false;
                }
                else{

                    $("#password_error").html('');
                    password_element.style.border = '1px solid #ced4da';
                }

                if(password_min_length>password.length)
                {
                    password_element.style.border = '1px solid red';

                    $("#password_error").html('Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

                    return false;
                }

                if(password_max_length<password.length)
                {
                    password_element.style.border = '1px solid red';

                    $("#password_error").html('Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

                    return false;
                }

                if(password!=confirm_password)
                {
                    confirm_password_element.style.border = '1px solid red';

                    $("#confirm_password_error").html('New Password And Confirm Password Word Must Be Same');

                    return false;
                }
                else{

                    $("#confirm_password_error").html('');
                    confirm_password_element.style.border = '1px solid #ced4da';
                }
                
                return true;
            }

            $(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>