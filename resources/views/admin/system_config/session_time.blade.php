<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Session Time Information (All <span class="span_red">*</span> Marked Are Mandatory)</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Session Idle Time In Minutes <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="session_time" name="session_time" placeholder="Enter Session Idle Time In Minutes" maxlength="3" value="{{$system_data['session_time']}}" tabindex="1" >
                        <span id="session_time-error" class="error">Please Session Idle Time In Minutes</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Session Check Time In Seconds <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" id="session_check_time" name="session_check_time" placeholder="Enter Session Check Time In Seconds" maxlength="3" value="{{$system_data['session_check_time']}}" tabindex="1" >
                        <span id="session_check_time-error" class="error">Please Session Check Time In Seconds </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation()" class="btn btn-primary float-right" tabindex="14">Update Session Time</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#session_time').focus();

    function validation(){

        if( form_validation('session_time#number*session_check_time#number','Session Idle Time In Minutes*Session Check Time In Seconds')==false ){

            return false;
        }

        confirm_box('Session Time', 'Are You Sure Want To Save Session Time?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("session_time", $("#session_time").val());
        form_data.append("session_check_time", $("#session_check_time").val());
    
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

                all_response(response,'{{route(request()->route()->getName())}}','Session Time');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>
    

