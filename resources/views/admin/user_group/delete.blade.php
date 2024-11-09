<div class="container-fluid">
    <div class="card card-default px-1 py-1">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="20%">Particular</th>
                    <th width="40%">
                        Details
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
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Code</td>
                    <td>{{$single_data->group_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Title</td>
                    <td>{{$single_data->group_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->group_details!!}</p></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Photo</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user_group')}}/{{$single_data->group_logo!=''?$single_data->group_logo:'user_group_logo.png'}}"/></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Icon</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user_group')}}/{{$single_data->group_icon!=''?$single_data->group_icon:'user_group_icon.png'}}"/></td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-12">
            <div class="form-group">
                <label>Delete Reason <span class="span_red">*</span></label>
                <textarea class="form-control" id="delete_reason" maxlength="250" name="delete_reason" tabindex="1" ></textarea>
                <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
                <input type="hidden" id="process_status" name="process_status" value="{{$single_data->process_status}}">
                <span id="delete_reason-error" class="error">Please Enter Delete Reason</span>
            </div>
        </div>
        <div class="card-footer">
            <button tabindex="2" type="button" id="return" onclick="validation();" class="btn btn-danger float-right">Delete</button>
        </div>
    </div>
</div> 

<script type="text/javascript">

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#delete_reason').focus();
    });

    function validation(){

        if(form_validation('delete_reason#text','Delete Reason')==false ){

            return false;
        }

        confirm_box('Delete', 'Are You Sure Want To Delete?','delete_data');
    }

    function delete_data(){
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}/{{$single_data->id}}",
            type: "POST",
            data: {
                delete_reason: $('#delete_reason').val(),
                actual_status: $('#actual_status').val(),
                process_status: $('#process_status').val(),
            },
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route('user_management.user_group.view')}}','Delete View User Group');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>
    