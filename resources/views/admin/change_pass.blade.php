<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Change Password | {{$system_data['system_title']}}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/system')}}/{{$system_data['system_icon']}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/adminlte.css')}}">
        <style type="text/css">
            .login-page{
                background: url('{{asset('uploads/system')}}/{{$system_data['system_bg_image']}}');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .input-error{
                color: red;
                display: inline-block;
                width:100%;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <p class="h1"><b>{{$system_data['system_name']}}</b></p>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Chnage Your Password</p>
                    @if(Session::has('message'))
                        <p class="text-center input-error">{{Session::get('message')}}</p>
                    @endif
                    <form method="post" action="{{route(request()->route()->getName())}}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="input-error" id="old_password_error">
                                @error('old_password'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" minlength="{{$system_data['password_min_length']}}" maxlength="{{$system_data['password_max_length']}}" name="new_password" id="new_password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="input-error" id="new_password_error">
                                @error('new_password'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" minlength="{{$system_data['password_min_length']}}" maxlength="{{$system_data['password_max_length']}}" name="c_password" id="c_password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="input-error" id="c_password_error">
                                @error('c_password'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" onclick="return chnage_password_validation();" class="btn btn-primary btn-block">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/adminlte.js')}}"></script>
        <script type="text/javascript">

            $('#old_password').focus();
            
            function chnage_password_validation(){

                var password_min_length =  {{$system_data['password_min_length']}};
                var password_max_length =  {{$system_data['password_max_length']}};

                var old_password = $("#old_password").val();
                var new_password = $("#new_password").val();
                var c_password = $("#c_password").val();

                var old_password_element = document.getElementById('old_password');
                var new_password_element = document.getElementById('new_password');
                var c_password_element = document.getElementById('c_password');

                var pattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{<?php echo $system_data['password_max_length'];?>,}$/;

                if(old_password==''){

                    old_password_element.style.border = '1px solid red';

                    $("#old_password_error").html('Old password Is Requrired');

                    return false;
                }
                else{

                	$("#old_password_error").html('');
                    old_password_element.style.border = '1px solid #ced4da';
                }

                if (!pattern.test(new_password)){

                	new_password_element.style.border = '1px solid red';

                    $("#new_password_error").html('Password  Must Contain At Least One letter, One digit, And Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

                    return false;
            	}
            	else{

            		$("#new_password_error").html('');
                    new_password_element.style.border = '1px solid #ced4da';
                }

                if(password_min_length>new_password.length)
                {
                    new_password_element.style.border = '1px solid red';

                    $("#new_password_error").html('Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

                    return false;
                }

                if(password_max_length<new_password.length)
                {
                    new_password_element.style.border = '1px solid red';

                    $("#new_password_error").html('Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

                    return false;
                }

                if(new_password!=c_password)
                {
                	c_password_element.style.border = '1px solid red';

                    $("#c_password_error").html('New Password And Confirm Password Word Must Be Same');

                    return false;
                }
                else{

                	$("#c_password_error").html('');
                    c_password_element.style.border = '1px solid #ced4da';
                }
                
                return true;
            }
        </script>
    </body>
</html>
