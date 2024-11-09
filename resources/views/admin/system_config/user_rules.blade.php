<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">User Id & Password Rules Information (All <span class="span_red">*</span> Marked Are Mandatory)</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password Validity Period in Days <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="password_validity_days" name="password_validity_days" placeholder="Enter Password Validity Period in Days" value="{{$system_data['password_validity_days']}}" minlength="2" maxlength="100" tabindex="1" autofocus>
                        <span id="password_validity_days-error" class="error">Please Enter Password Validity Period in Days</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password Minimum Lenght <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="password_min_length" name="password_min_length" placeholder="Enter Password Minimum Lenght" value="{{$system_data['password_min_length']}}" minlength="1" maxlength="2" tabindex="2">
                        <span id="password_min_length-error" class="error">Please Enter Password Minimum Lenght</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Password Maximum Lenght <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="password_max_length" name="password_max_length" placeholder="Enter Password Maximum Lenght" value="{{$system_data['password_max_length']}}" minlength="1" maxlength="2" tabindex="3">
                        <span id="password_max_length-error" class="error">Please Enter Password Maximum Lenght</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Default Password Type <span class="span_red">*</span></label>
                        <select class="form-control selectpicker" id="default_password_type" name="default_password_type" tabindex="4" title="Select Default Password Type" data-live-search="true">
                            <option value="1">Random</option>
                            <option value="2">User Id</option>
                            <option value="3">Dot</option>
                        </select>
                        <span id="default_password_type-error" class="error">Select Default Password Type</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Id Minimum Lenght <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="userid_min_length" name="userid_min_length" placeholder="Enter User Id Minimum Lenght" minlength="1" maxlength="2" value="{{$system_data['userid_min_length']}}" tabindex="5">
                        <span id="userid_min_length-error" class="error">Please Enter User Id Minimum Lenght</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Id Maximum Lenght <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="userid_max_length" name="userid_max_length" placeholder="Enter User Id Maximum Lenght" minlength="1" maxlength="2" value="{{$system_data['userid_max_length']}}" tabindex="6">
                        <span id="userid_max_length-error" class="error">Please Enter User Id Maximum Lenght</span>
                    </div>
                </div>                    
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="14">Update User Rules</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#default_password_type').selectpicker();

    $("#default_password_type").val({{$system_data['default_password_type']}}).selectpicker('refresh');

    $('#password_validity_days').focus();

    function validation(){

        if( form_validation('password_validity_days#number*password_min_length#number*password_max_length#number*default_password_type#select*userid_min_length#number*userid_max_length#number','Password Validity Period in Days*Password Minimum Lenght*Password Maximum Lenght*Default Password Type*User Id Minimum Lenght*User Id Maximum Lenghtt')==false ){

            return false;
        }

        confirm_box('User Id & Password Rules', 'Are You Sure Want To Save User Id & Password Rules?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("password_validity_days", $("#password_validity_days").val());
        form_data.append("password_min_length", $("#password_min_length").val());
        form_data.append("password_max_length", $("#password_max_length").val());
        form_data.append("default_password_type", $("#default_password_type").val());
        form_data.append("userid_min_length", $("#userid_min_length").val());
        form_data.append("userid_max_length", $("#userid_max_length").val());
    
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

                all_response(response,'{{route(request()->route()->getName())}}','User Rules');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>
    

