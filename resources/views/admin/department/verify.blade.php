<div class="container-fluid">
    <div class="card card-default px-1 py-1">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="20%">Particular</th>
                    <th width="40%">Original Data</th>
                    <th width="40%">
                        Checnged Data
                        <button onclick="get_activity_history_page('{{$single_data->department_name}}','Department Activity','{{route('activity.history',[$single_data->id, 'departments'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->department_name}}','Department History','{{route('reference.department.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Name</td>
                    <td>{{$single_data->department_name}}</td>
                    <td>{{isset($history_data->department_name)?$history_data->department_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Code</td>
                    <td>{{$single_data->department_code}}</td>
                    <td>{{isset($history_data->department_code)?$history_data->department_code:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Title</td>
                    <td>{{$single_data->department_title}}</td>
                    <td>{{isset($history_data->department_title)?$history_data->department_title:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                    <td>{{isset($history_data->status)?$data_status[$history_data->status]:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->department_details!!}</p></td>
                    <td>{!!isset($history_data->department_details)?$history_data->department_details:''!!}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Operational Status</td>
                    <td colspan="2">{{isset($actual_status[$single_data->actual_status])?$actual_status[$single_data->actual_status]:''}} {{isset($process_status[$single_data->process_status])?$process_status[$single_data->process_status]:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Maker By</td>
                    <td colspan="2">{{$single_data->maker_by}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Maker Date Time</td>
                    <td colspan="2">{{format_datetime_view($single_data->sent_checker_at)}}</td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-12">
            <div class="form-group">
                <label>Return Reason <span class="span_green">*</span></label>
                <textarea class="form-control" id="return_reason" maxlength="250" name="return_reason" tabindex="1" ></textarea>
                <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
                <input type="hidden" name="verify_type" value="0" id="verify_type">
                <span id="return_reason-error" class="error">Please Enter Return Reason</span>
            </div>
        </div>
        <div class="card-footer">
            <button tabindex="3" type="button" id="return" onclick="validation(0)" class="btn btn-danger float-right">Return</button>
            <button tabindex="2" type="button" id="verify" onclick="validation(1);" class="btn btn-primary float-right mr-2">Approve</button>
        </div>
    </div>
</div> 

<script type="text/javascript">

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#return_reason').focus();
    });

    function validation(verify_type){

        $('#verify_type').val(verify_type);

        if(verify_type==0){

            if(form_validation('return_reason#text','Return Reason')==false ){

                return false;
            }

            confirm_box('Return', 'Are You Sure Want To Return?','verify');
        }
        else{

            confirm_box('Approve', 'Are You Sure Want To Approve?','verify');
        }
    }

    function verify(){
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}/{{$single_data->id}}",
            type: "POST",
            data: {
                return_reason: $('#return_reason').val(),
                verify_type: $('#verify_type').val(),
                actual_status: $('#actual_status').val()
            },
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route(request()->route()->getName())}}','Verify View Department');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>