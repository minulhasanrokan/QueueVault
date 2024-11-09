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
                    @if(Session::has('message') && Session::has('create_status') && Session::get('create_status')==1 && Session::get('verify_mail')==0)
                        <h5 class="text-center">A Reset Password Link Has Been Sent To The E-mail Address You Provided During Registration.</h5>
                        <a class="btn btn-primary btn-block" style="text-decoration: none; color:white;" href="{{route('forgot.resend.password',Session::get('user_id'))}}">Resend Reset Password Email</a>
                    @elseif(Session::has('message') && Session::has('create_status') && Session::get('create_status')==1 && Session::get('verify_mail')==1)
                        <h5 class="text-center">{{Session::get('message')}}</h5>
                        <a class="btn btn-primary btn-block" style="text-decoration: none; color:white;" href="{{route('resend.verify.email',Session::get('user_id'))}}">Send Verification E-mail</a>
                    @else
                    <p class="login-box-msg">Reset Password</p>
                    @if(Session::has('message'))
                        <p class="text-center input-error" style="color: red;">{{Session::get('message')}}</p>
                    @endif
                    <form action="{{route('forgot.password')}}" method="post">
                        @csrf   
                        <div class="input-group mb-3">
                            <input type="email" name="email" tabindex="1" id="email" class="form-control" placeholder="Enter Your E-mail Address" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <div class="input-error" id="email_error">
                                @error('email'){{ $message }}@enderror
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="input-group mb-3">
                            <button type="submit" tabindex="2" onclick=" return form_validation();" class="btn btn-primary btn-block">Send Reset Password Link</button>
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

            $('#email').focus();
            
            function form_validation(){

                var emailValidator = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                var email = $("#email").val();

                var email_element = document.getElementById('email');

                if(emailValidator.test(email)==false){

                    email_element.style.border = '1px solid red';

                    return false;
                }
                else{

                    email_element.style.border = '1px solid #ced4da';
                }
                
                return true;
            }

            $(".input-error").delay(3000).fadeOut(800); 
        </script>
    </body>
</html>