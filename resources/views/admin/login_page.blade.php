<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Log in | {{$system_data['system_title']}}</title>
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
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <p class="h1"><b>{{$system_data['system_name']}}</b></p>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Sign In To Start Your Session</p>
                    @if(Session::has('message'))
                        <p class="text-center input-error">{{Session::get('message')}}</p>
                    @endif
                    <form method="post" action="{{route('admin.login.store')}}">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" tabindex="1" id="email" class="form-control" placeholder="E-mail">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <div class="input-error" id="email_error">
                                @error('email'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" name="password" tabindex="2" id="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <div class="input-error" id="password_error">
                                @error('password'){{ $message }}@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" tabindex="3" onclick="return login_form_validation();" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                    <p class="mb-1 mt-3 text-center">
                        <a href="{{route('forgot.password')}}">Forgot Password</a>
                    </p>
                </div>
            </div>
        </div>
        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/adminlte.js')}}"></script>
        <script type="text/javascript">

            $('#email').focus();
            
            function login_form_validation(){

                var emailValidator = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                var email = $("#email").val();
                var password = $("#password").val();

                var email_element = document.getElementById('email');
                var password_element = document.getElementById('password');

                if(emailValidator.test(email)==false){

                    email_element.style.border = '1px solid red';

                    return false;
                }
                else{

                    email_element.style.border = '1px solid #ced4da';
                }

                if(password==''){

                    password_element.style.border = '1px solid red';

                    return false;
                }
                else{

                    password_element.style.border = '1px solid #ced4da';
                }
                
                return true;
            }

            $(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>
