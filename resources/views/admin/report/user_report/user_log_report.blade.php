<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Employee/User ID</label>
                        <div id="user_id_container"></div>
                        <span id="user_id-error" class="error">Please Select Employee/User ID</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Login From Date <span class="span_red">*</span></label>
                        <div class="input-group">
                            <input type="text" tabindex="2" id="from_date" readonly placeholder="dd/mm/yyyy" name="from_date" class="form-control float-right">
                        </div>
                        <span id="from_date-error" class="error">Please Insert Valid Login From Date</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Login To Date <span class="span_red">*</span></label>
                        <div class="input-group">
                            <input type="text" id="to_date" tabindex="3" readonly placeholder="dd/mm/yyyy" name="to_date" class="form-control float-right">
                        </div>
                        <span id="to_date-error" class="error">Please Insert Valid Login To Date</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label></label>
                        <div class="input-group" ><button type="button" onclick="validation();" class="btn btn-primary" tabindex="4">Search</button></div>
                    </div>
                </div>
        </div>
    </div>
    <div class="card card-default px-2 py-2">
        <div class="card-body table-responsive p-0" style="height: 500px;">
            <table class="table table-bordered table-head-fixed" id="user_log_report_table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Employee/User Name</th>
                        <th>Employee/User ID</th>
                        <th>IP Address</th>
                        <th>Login Device</th>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>Last Active Time</th>
                        <th>Logout Status</th>
                        <th>Force Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('users','id,name','user_id','user_id_container','Select Employee/User ID',0,0,0,1,'',0,0,0,0);
    
    $('#user_id').focus();

    make_datepicker('from_date');
    make_datepicker('to_date');

    function validation(){
        
        if( form_validation('from_date#date*to_date#date','Login From Date*Login To Date')==false ){

            return false;
        }

        if( two_date_validation('from_date','to_date')==false ){

            return false;
        }

        confirm_box('Upser Log Report', 'Are You Sure Want To View User Log Report?','view_report');
    }

    function view_report(){

        $("#user_log_report_table").find('tbody').html('');

        var form_data = new FormData();

        form_data.append("user_id", $("#user_id").val());
        form_data.append("right_group_id", $("#right_group_id").val());
        form_data.append("right_category_id", $("#right_category_id").val());
        form_data.append("right_id", $("#right_id").val());
        form_data.append("actual_process_status_id", $("#actual_process_status_id").val());
        form_data.append("from_date", $("#from_date").val());
        form_data.append("to_date", $("#to_date").val());
    
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

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    var data = response;

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);

                    if (data.errors && data.success==false) {

                        $.each(data.errors, function(field, errors) {

                            $("#" + field).css("border-color", "red");
                            $("#" +field+ "-error").html(errors);
                            $("#" + field + "-error").show();
                        });
                    }
                    else{

                        switch(data.alert_type){

                            case 'info':
                            toastr.info(data.message);
                            break;

                            case 'success':
                            toastr.success(data.message);
                            break;

                            case 'warning':
                            toastr.warning(data.message);
                            break;

                            case 'error':
                            toastr.error(data.message);
                            break; 
                        }

                        if(data.alert_type=='success'){

                            var sl = 1;
                            data.report_data.forEach(function(row) {

                                var htmltext = "<tr><td>"+sl+"</td><td>"+row.user_name+"</td><td>"+row.user_id+"</td><td>"+row.ip_address+"</td><td>"+row.user_agent+"</td><td>"+formatDatetime(row.log_in_time)+"</td><td>"+formatDatetime(row.log_out_time)+"</td><td>"+formatDatetime(row.last_active_time)+"</td><td>"+yes_no_status[row.log_out_status]+"</td><td>"+yes_no_status[row.force_status]+"</td></tr>";

                                $("#user_log_report_table").find('tbody').append(htmltext);

                                sl++;
                            });
                        }
                    }

                    setTimeout(function() {

                        $(".form-control").css("border-color", "");
                    }, 3000);

                    $(".error").delay(3000).fadeOut(800);
                }

                hideLoading(1000);
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>