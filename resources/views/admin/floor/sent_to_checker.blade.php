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
                        <button onclick="get_activity_history_page('{{$single_data->floor_name}}','Floor Activity','{{route('activity.history',[$single_data->id, 'floors'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->floor_name}}','Floor History','{{route('reference.floor.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Branch Name</td>
                    <td>{{$single_data->branch_name}}</td>
                    <td>{{isset($history_data->branch_name)?$history_data->branch_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Assign Service</td>
                    <td>{{App\Models\Common::get_assign_data($single_data->assign_service,'services','id','service_name','comma')}}</td>
                    <td>{{App\Models\Common::get_assign_data($history_data->assign_service,'services','id','service_name','comma')}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Floor Name</td>
                    <td>{{$single_data->floor_name}}</td>
                    <td>{{isset($history_data->floor_name)?$history_data->floor_name:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Floor Code</td>
                    <td>{{$single_data->floor_code}}</td>
                    <td>{{isset($history_data->floor_code)?$history_data->floor_code:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Floor Title</td>
                    <td>{{$single_data->floor_title}}</td>
                    <td>{{isset($history_data->floor_title)?$history_data->floor_title:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Floor Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                    <td>{{isset($history_data->status)?$data_status[$history_data->status]:''}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->floor_details!!}</p></td>
                    <td>{!!isset($history_data->floor_details)?$history_data->floor_details:''!!}</td>
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

                all_response(response,'{{route(request()->route()->getName())}}','Sent To Checker View Floor');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>
    