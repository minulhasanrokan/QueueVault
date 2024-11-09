@php
	
	$session_data = \App\Http\Controllers\CommonController::get_session_data();

    $user_name = $session_data['name'];
    $user_photo = $session_data['user_photo'];
    $admin_status = $session_data['super_admin_status'];

@endphp

<!DOCTYPE html>
<html lang="en">
	<head>
	  	<meta charset="utf-8">
	  	<meta name="viewport" content="width=device-width, initial-scale=1">
	  	<meta name="csrf-token" content="{{ csrf_token() }}">
	  	<title>Lock | {{$system_data['system_title']}}</title>
	  	<link rel="icon" type="image/x-icon" href="{{asset('uploads/system')}}/{{$system_data['system_icon']}}">
	  	<link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
	  	<link rel="stylesheet" href="{{asset('assets/css/adminlte.css')}}">
	  	<style type="text/css">
            .lockscreen{
                background:url('{{asset('uploads/system')}}/{{$system_data['system_bg_image']}}');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            .input-error{
                color: red;
            }
        </style>
	</head>
	<body class="hold-transition lockscreen">
		<div class="lockscreen-wrapper">
	  		<div class="lockscreen-logo">{{$system_data['system_name']}}</div>
	  		@if(Session::has('message'))
                <p class="text-center input-error">{{Session::get('message')}}</p>
            @endif
	  		<div class="lockscreen-name">{{$user_name!=''?$user_name:'User'}}</div>
		  	<div class="lockscreen-item">
		    	<div class="lockscreen-image">
		      		<img src="{{asset('uploads/user')}}/{{$user_photo!=''?$user_photo:'user.png'}}" alt="{{$user_name!=''?$user_name:'User'}}">
		    	</div>
		    	<form class="lockscreen-credentials" method="post" id="lock_form">
		    		@csrf
			      	<div class="input-group">
			        	<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
			        	<div class="input-group-append">
			          		<button onclick="submit_lock_form();" type="button" class="btn">
			            		<i class="fas fa-arrow-right text-muted"></i>
			          		</button>
			        	</div>
			      	</div>
		    	</form>
		  	</div>
	  		<div class="text-center">
	    		<a href="{{route('logout')}}">Or Sign In As A Different User</a>
	  		</div>
		</div>
		<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script type="text/javascript">

        	$('#password').focus();
        	
        	document.addEventListener('keydown', function(event) {
    			
			    var code = event.code;

			    if(code=='Enter'){

			    	submit_lock_form();
			    }
			});

        	function submit_lock_form(){

        		var password = $("#password").val();

        		if(password==''){

        			$("#password").focus();

                    alert('Please Input You Password');

                    return false;
                }

                $("#lock_form").submit();
        	}
        </script>
	</body>
</html>

