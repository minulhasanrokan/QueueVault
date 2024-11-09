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

                all_response(response,'{{route('user_management.user_group.view')}}','Sent To Checker View User Group');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>
    