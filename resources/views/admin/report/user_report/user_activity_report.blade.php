<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Employee/User ID</label>
                        <div id="user_id_container"></div>
                        <span id="user_id-error" class="error">Please Select Employee/User ID</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Menu Group</label>
                        <div id="right_group_id_container"></div>
                        <span id="right_group_id-error" class="error">Please Select Menu Group</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Menu Category</label>
                        <div id="right_category_id_container"></div>
                        <span id="right_category_id-error" class="error">Please Select Menu Category</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>Menu</label>
                        <div id="right_id_container"></div>
                        <span id="right_id-error" class="error">Please Select Menu</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Activity Status</label>
                        <div id="actual_process_status_id_container"></div>
                        <span id="actual_process_status_id-error" class="error">Please Select Activity Status</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>From Date <span class="span_red">*</span></label>
                        <div class="input-group">
                            <input type="text" tabindex="6" id="from_date" readonly placeholder="dd/mm/yyyy" name="from_date" class="form-control float-right">
                        </div>
                        <span id="from_date-error" class="error">Please Insert Valid From Date</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>To Date <span class="span_red">*</span></label>
                        <div class="input-group">
                            <input type="text" tabindex="7" id="to_date" readonly placeholder="dd/mm/yyyy" name="to_date" class="form-control float-right">
                        </div>
                        <span id="to_date-error" class="error">Please Insert Valid To Date</span>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label></label>
                        <div class="input-group">
                            <button type="button" onclick="validation();" class="btn btn-primary" tabindex="8">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-default px-2 py-2">
            <div class="card-body table-responsive p-0" style="height: 500px;">
                <table class="table table-bordered table-head-fixed" id="user_activity_report_table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Employee/User Name</th>
                            <th>Employee/User ID</th>
                            <th>Menu Group</th>
                            <th>Menu Category</th>
                            <th>Menu</th>
                            <th>Activity Status</th>
                            <th>Activity Datetime</th>
                            <th>Activity Details</th>
                            <th>History</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('users','id,name','user_id','user_id_container','Select Employee/User ID',0,0,0,1,'',0,0,0,0);
    load_drop_down('right_groups','id,name','right_group_id','right_group_id_container','Select Menu Group',0,0,0,2,'load_right_category();',0,0,0,0);

    blank_drop_down('right_category_id','right_category_id_container','Select Menu Category',0,0,3);
    blank_drop_down('right_id','right_id_container','Select Menu Right',0,0,4);
    load_drop_down('actual_process_statuses','id,status_name','actual_process_status_id','actual_process_status_id_container','Activuty Status',0,0,0,5,'',0,0,0,0);
    
    $('#user_id').focus();

    make_datepicker('from_date');
    make_datepicker('to_date');

    function load_right_category(){

        load_drop_down('right_categories','id,c_name','right_category_id','right_category_id_container','Select Menu Category',0,0,0,3,'load_right();','group_id',$("#right_group_id").val(),0,0);

        blank_drop_down('right_id','right_id_container','Select Menu Right',0,0,4);
    }

    function load_right(){

        load_drop_down('right_details','id,r_name','right_id','right_id_container','Select Menu Right',0,0,0,4,'','cat_id',$("#right_category_id").val(),0,0);
    }

    function validation(){
        
        if( form_validation('from_date#date*to_date#date','From Date*To Date')==false ){

            return false;
        }

        if( two_date_validation('from_date','to_date')==false ){

            return false;
        }

        confirm_box('Upser Activity Report', 'Are You Sure Want To View User Activuty Report?','view_report');
    }

    function view_report(){

        $("#user_activity_report_table").find('tbody').html('');

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

                                var url = "<td>&nbsp;</td>";

                                if(row.table_name!='' && row.table_id!=0){

                                    url = "<td onclick=\"get_activity_history_page('Data','"+row.category_name+" Activity','{{route('activity.history')}}/"+row.table_id+"/"+row.table_name+"')\">Details</td>";
                                }

                                var htmltext = "<tr><td>"+sl+"</td><td>"+row.name+"</td><td>"+row.user_id+"</td><td>"+row.group_name+"</td><td>"+row.category_name +"</td><td>"+row.right_name+"</td><td>"+row.status_name+"</td><td>"+formatDatetime(row.created_at)+"</td><td>"+row.activity+"</td>"+url+"</tr>";

                                $("#user_activity_report_table").find('tbody').append(htmltext);

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