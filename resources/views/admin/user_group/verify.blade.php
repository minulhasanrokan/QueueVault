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
                        <button onclick="get_right_details_page('{{$single_data->group_name}}','User Group Right Details','{{route('user_management.user_group.right.details',$single_data->id)}}')"  class="btn btn-primary float-right ml-1">Right</button>
                        <button onclick="get_activity_history_page('{{$single_data->group_name}}','User Group Activity','{{route('activity.history',[$single_data->id, 'user_groups'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->group_name}}','User Group History','{{route('user_management.user_group.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Name</td>
                    <td>{{$single_data->group_name}}</td>
                    <td>{{isset($history_data->group_name)?$history_data->group_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Code</td>
                    <td>{{$single_data->group_code}}</td>
                    <td>{{isset($history_data->group_code)?$history_data->group_code:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Title</td>
                    <td>{{$single_data->group_title}}</td>
                    <td>{{isset($history_data->group_title)?$history_data->group_title:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                    <td>{{isset($history_data->status)?$data_status[$history_data->status]:''}}</td>
                </tr>
                @if($single_data->actual_status==8)
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Right</td>
                    <td><button type="button" onclick="get_right_details_page('{{$single_data->group_name}}','User Group Right Details','{{route('user_management.user_group.right.details',$single_data->id)}}')" class="btn btn-primary">Original Right</button></td>
                    <td><button type="button" onclick="get_right_details_page('{{$single_data->group_name}}','User Group Right Chnage Details','{{route('user_management.user_group.chenged.right.details',$single_data->id)}}')" class="btn btn-primary">Changed Right</button></td>
                </tr>
                @endif
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->group_details!!}</p></td>
                    <td>{!!isset($history_data->group_details)?$history_data->group_details:''!!}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Photo</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user_group')}}/{{isset($single_data->group_logo)?$single_data->group_logo:'user_group_logo.png'}}"/></td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user_group')}}/{{isset($history_data->group_logo)?$history_data->group_logo:'user_group_logo.png'}}"/></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Icon</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user_group')}}/{{isset($single_data->group_icon)?$single_data->group_icon:'user_group_icon.png'}}"/></td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user_group')}}/{{isset($history_data->group_icon)?$history_data->group_icon:'user_group_icon.png'}}"/></td>
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

                all_response(response,'{{route('user_management.user_group.view')}}','Verify View User Group');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>
    