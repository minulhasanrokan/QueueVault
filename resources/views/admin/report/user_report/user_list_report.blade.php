<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>User Status</label>
                        <div id="status_container">
                            <select tabindex="5" name="status" class="bootstrap-select form-control" id="status" title="Select User Status" data-live-search="true">
                                <option value="">Select User Status</option>
                                <option value="1">Active</option>
                                <option value="0">Deactive</option>
                            </select>
                        </div>
                        <span id="status-error" class="error">Please Select User Status</span>
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
            <table class="table table-bordered table-head-fixed" id="user_list_report_table">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Employee/User Name</th>
                        <th>User ID</th>
                        <th>E-mail</th>
                        <th>Mobile</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Branch</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#status').selectpicker();
    
    $('#status').focus();

    function validation(){

        confirm_box('Upser List Report', 'Are You Sure Want To View User List Report?','view_report');
    }

    function view_report(){

        $("#user_list_report_table").find('tbody').html('');

        var form_data = new FormData();

        form_data.append("status", $("#status").val());
    
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

                                var htmltext = "<tr><td>"+sl+"</td><td>"+row.name+"</td><td>"+row.user_id+"</td><td>"+row.email+"</td><td>"+row.mobile+"</td><td>"+row.designation_name+"</td><td>"+row.department_name+"</td><td>"+row.branch_name+"</td><td>"+data_status[row.status]+"</td></tr>";

                                $("#user_list_report_table").find('tbody').append(htmltext);

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