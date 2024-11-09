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
                    <td>{{isset($history_data->name)?$history_data->name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User ID</td>
                    <td>{{$single_data->user_id}}</td>
                    <td>{{isset($history_data->user_id)?$history_data->user_id:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Group</td>
                    <td>{{$single_data->group_name}}</td>
                    <td>{{isset($history_data->group_name)?$history_data->group_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Branch</td>
                    <td>{{$single_data->branch_name}}</td>
                    <td>{{isset($history_data->branch_name)?$history_data->branch_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Department</td>
                    <td>{{$single_data->department_name}}</td>
                    <td>{{isset($history_data->department_name)?$history_data->department_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Assign Department</td>
                    <td>{{App\Models\Common::get_assign_data($single_data->assign_department,'departments','id','department_name','comma')}}</td>
                    <td>{{App\Models\Common::get_assign_data($history_data->assign_department,'departments','id','department_name','comma')}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Designation</td>
                    <td>{{$single_data->designation_name}}</td>
                    <td>{{isset($history_data->designation_name)?$history_data->designation_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Address</td>
                    <td>{{$single_data->address}}</td>
                    <td>{{isset($history_data->address)?$history_data->address:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Modile</td>
                    <td>{{$single_data->mobile}}</td>
                    <td>{{isset($history_data->mobile)?$history_data->mobile:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User E-mail</td>
                    <td>{{$single_data->email}}</td>
                    <td>{{isset($history_data->email)?$history_data->email:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                    <td>{{isset($history_data->status)?$data_status[$history_data->status]:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->details!!}</p></td>
                    <td>{!!isset($history_data->details)?$history_data->details:''!!}</td>
                </tr>
                @if($single_data->actual_status==8)
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Group Right</td>
                    <td><button type="button" onclick="get_right_details_page('{{$single_data->group_name}}','User Group Right Details','{{route('user_management.user.right.details',$single_data->id)}}')" class="btn btn-primary">Original Right</button></td>
                    <td><button type="button" onclick="get_right_details_page('{{$single_data->group_name}}','User Group Right Chnage Details','{{route('user_management.user.chenged.right.details',$single_data->id)}}')" class="btn btn-primary">Changed Right</button></td>
                </tr>
                @endif
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Photo</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user')}}/{{$single_data->user_photo!=''?$single_data->user_photo:'user.png'}}"/></td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user')}}/{{$history_data->user_photo!=''?$history_data->user_photo:'user.png'}}"/></td>
                </tr>
            </tbody>
        </table>
        <div class="card-footer">
            <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="1">Send To Checker</button>
        </div>
    </div>
</div> 

<script type="text/javascript">

    function validation(){

        confirm_box('Send To Checker', 'Are You Sure Want To Send To Checker?','send_to_checker');
    }

    function send_to_checker(){
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}/{{$single_data->id}}",
            type: "POST",
            data: {
                actual_status: $('#actual_status').val()
            },
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route(request()->route()->getName())}}','Sent To Checker View Employee/User');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>
    