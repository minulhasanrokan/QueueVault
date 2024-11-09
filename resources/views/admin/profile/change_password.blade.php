<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Chnage Password</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Old Password <span class="span_red">*</span></label>
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter Old Password" tabindex="1" autofocus>
                        <span id="old_password-error" class="error">Please Enter Old Password</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>New Password <span class="span_red">*</span></label>
                        <input type="password" minlength="{{$system_data['password_min_length']}}" maxlength="{{$system_data['password_max_length']}}" class="form-control" id="password" name="password" placeholder="Enter New Password" tabindex="2">
                        <span id="password-error" class="error">Please Enter New Password</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Confirm New Password <span class="span_red">*</span></label>
                        <input type="password" minlength="{{$system_data['password_min_length']}}" maxlength="{{$system_data['password_max_length']}}" class="form-control" id="c_password" name="c_password" placeholder="Enter Confirm New Password"  tabindex="3">
                        <span id="c_password-error" class="error">Please Enter Confirm New Password</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="4">Update Password</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#old_password').focus();

    function validation(){

        if( form_validation('old_password#text*password#text*c_password#text','Old Password*New Password*Confirm New Password')==false ){

            return false;
        }

        var password_min_length =  {{$system_data['password_min_length']}};
        var password_max_length =  {{$system_data['password_max_length']}};

        var old_password = $("#old_password").val();
        var password = $("#password").val();
        var c_password = $("#c_password").val();

        var pattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{<?php echo $system_data['password_max_length'];?>,}$/;

        if (!pattern.test(password)){

            $("#password-error").html('Password  Must Contain At Least One letter, One digit, And Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');
            $("#password-error").show();

            return false;
        }
        else{

            $("#password-error").hide();
        }

        if(password_min_length>password.length)
        {
            $("#password-error").show();

            $("#password-error").html('Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

            return false;
        }
        else{
            $("#password-error").hide();
        }

        if(password_max_length<password.length)
        {
            $("#password-error").show();

            $("#password=error").html('Password  Must Be Between '+password_min_length+' To '+password_max_length+' Characters');

            return false;
        }
        else{
            $("#password-error").hide();
        }

        if(password!=c_password)
        {
            $("#c_password-error").show();

            $("#c_password-error").html('New Password And Confirm New Password Word Must Be Same');

            return false;
        }
        else{

            $("#c_password-error").html('');
        }

        confirm_box('System Info', 'Are You Sure Want To Update Your Password?','update_data');
    }

    function update_data(){

        var form_data = new FormData();

        form_data.append("old_password", $("#old_password").val());
        form_data.append("password", $("#password").val());
        form_data.append("c_password", $("#c_password").val());
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route(request()->route()->getName())}}','Change Password');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>