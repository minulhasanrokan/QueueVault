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
                        <button onclick="get_right_details_page('{{$single_data->name}}','User Right Details','{{route('user_management.user.right.details',$single_data->id)}}')"  class="btn btn-primary float-right ml-1">Right</button>
                        <button onclick="get_activity_history_page('{{$single_data->name}}','User Activity','{{route('activity.history',[$single_data->id, 'users'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->name}}','User History','{{route('user_management.user.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Name</td>
                    <td>{{$single_data->name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User ID</td>
                    <td>{{$single_data->user_id}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Group</td>
                    <td>{{$single_data->group_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Branch</td>
                    <td>{{$single_data->branch_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Department</td>
                    <td>{{$single_data->department_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Assign Department</td>
                    <td>{{App\Models\Common::get_assign_data($single_data->assign_department,'departments','id','department_name','comma')}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Designation</td>
                    <td>{{$single_data->designation_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Address</td>
                    <td>{{$single_data->address}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Modile</td>
                    <td>{{$single_data->mobile}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User E-mail</td>
                    <td>{{$single_data->email}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->details!!}</p></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Photo</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user')}}/{{$single_data->user_photo!=''?$single_data->user_photo:'user.png'}}"/></td>
                </tr>
            </tbody>
        </table>
        <div class="col-md-12">
            <div class="form-group">
                <label>Active Reason <span class="span_red">*</span></label>
                <textarea class="form-control" id="active_reason" maxlength="250" name="active_reason" tabindex="1" ></textarea>
                <span id="active_reason-error" class="error">Please Input Active Reason</span>
            </div>
        </div>
        <div class="card-footer">
            <button tabindex="2" type="button" id="return" onclick="validation();" class="btn btn-danger float-right">Active</button>
        </div>
    </div>
</div> 

<script type="text/javascript">

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#active_reason').focus();
    });

    function validation(){

        if(form_validation('active_reason#text','Active Reason')==false ){

            return false;
        }

        confirm_box('Active', 'Are You Sure Want To Active?','active');
    }

    function active(){
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}/{{$single_data->id}}",
            type: "POST",
            data: {
                active_reason: $('#active_reason').val()
            },
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route(request()->route()->getName())}}','Active View Employee/User');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>