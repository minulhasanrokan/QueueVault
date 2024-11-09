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
                        <button onclick="get_activity_history_page('{{$single_data->department_name}}','Department Activity','{{route('activity.history',[$single_data->id, 'departments'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->department_name}}','Department History','{{route('reference.department.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Name</td>
                    <td>{{$single_data->department_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Code</td>
                    <td>{{$single_data->department_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Title</td>
                    <td>{{$single_data->department_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Department Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->department_details!!}</p></td>
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

                all_response(response,'{{route(request()->route()->getName())}}','Delete View Department');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>