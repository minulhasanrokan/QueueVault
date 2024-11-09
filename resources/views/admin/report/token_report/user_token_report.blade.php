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
                        <label>From Date <span class="span_red">*</span></label>
                        <div class="input-group">
                            <input type="text" tabindex="100px" id="from_date" readonly placeholder="dd/mm/yyyy" name="from_date" class="form-control float-right">
                        </div>
                        <span id="from_date-error" class="error">Please Insert Valid From Date</span>
                    </div>
                </div>
                <div class="col-md-2">
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
            <div class="card-body table-responsive p-0">
                <table id="counter_data_table" class="table table-bordered table-striped text-center" rules="all">
                    <thead>
                        <tr>
                            <th width="100px">User Name</th>
                            <th width="100px">Branch</th>
                            <th width="100px">Service</th>
                            <th width="100px">FLoor</th>
                            <th width="100px">Counter</th>
                            <th width="100px">Token</th>
                            <th width="100px">Reference No</th>
                            <th width="100px">Call Status</th>
                            <th width="100px">VIP Status</th>
                            <th width="100px">Call Date</th>
                            <th width="100px">Start Time</th>
                            <th width="100px">End Time</th>
                            <th width="100px">Waiting Time</th>
                            <th width="100px">Counter Time</th>
                            <th width="100px">Total Time</th>
                            <th width="80px">Total Call</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('users','id,name','user_id','user_id_container','Select Employee/User ID',1,0,0,1,'',0,0,0,0);
    
    $('#user_id').focus();

    make_datepicker('from_date');
    make_datepicker('to_date');

    function validation(){
        
        if( form_validation('from_date#date*to_date#date','From Date*To Date')==false ){

            return false;
        }

        if( two_date_validation('from_date','to_date')==false ){

            return false;
        }

        confirm_box('User Wise Token Report', 'Are You Sure Want To View User Wise Token Report?','view_report');
    }

    make_data_table('counter_data_table');
    data_table_height('counter_data_table',350);

    function view_report(){

        if ($.fn.DataTable.isDataTable('#counter_data_table')) {
            $('#counter_data_table').DataTable().clear().destroy();
        }

        $('#counter_data_table').DataTable({
            "lengthMenu": [20, 50, 100,200,500],
            "pageLength": 20,
            "serverSide": true,
            "responsive": false,
            "colReorder": false,
            "scrollX": true, 
            "autoWidth": true,
            "scrollY": "350px",
            "scrollCollapse": false,
            "searching": false,
            "ajax":{
                "url": "{{route(request()->route()->getName())}}",
                "type": "POST",
                "data": function(d) {

                    var user_id = $("#user_id").val();

                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                    d.user_id = user_id;
                },
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                    showLoading();
                },
                "error": function(xhr, status, error) {

                    response_error_alert();
                },
                
                "dataSrc": function ( data ) {

                    if (data.errors && data.success === false) {
                        $.each(data.errors, function (field, errors) {
                            $("#" + field).css("border-color", "red");
                            $("#" + field + "-error").html(errors);
                            $("#" + field + "-error").show();
                        });
                    } 
                    else {

                        switch (data.alert_type) {
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
                    }

                    setTimeout(function () {
                        $(".form-control").css("border-color", "");
                    }, 3000);

                    $(".error").delay(3000).fadeOut(800);

                    return grid_response(data);
                }
            },
            "order": [
                [0, 'desc']
            ],
            "fnRowCallback": function(nRow, data, iDisplayIndex, iDisplayIndexFull) {

                if(data.not_show_status==0 && data.served_status==0){
                    $('td', nRow).css('background-color', 'red');
                }
                else if(data.not_show_status==1){
                    $('td', nRow).css('background-color', 'yellow');
                }
            },
            "columns": [

                {
                    "data": "user_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "branch_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "service_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "floor_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "counter_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "token_number",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "reference_no",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "call_status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+yes_no_status[data]+'</div>';
                    }
                },
                {
                    "data": "vip_status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+yes_no_status[data]+'</div>';
                    }
                },
                {
                    "data": "called_date",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+formatDate(data)+'</div>';
                        }
                    }
                },
                {
                    "data": "started_at",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+formatDatetime(data)+'</div>';
                        }
                    }
                },
                {
                    "data": "complete_at",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+formatDatetime(data)+'</div>';
                        }
                    }
                },
                {
                    "data": "waiting_time",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+data+'</div>';
                        }
                    }
                },
                {
                    "data": "counter_time",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+data+'</div>';
                        }
                    }
                },
                {
                    "data": "turn_around_time",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+data+'</div>';
                        }
                    }
                },
                {
                    "data": "current_call_status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                }
            ]
        });

        hideLoading(1000);
    }

</script>