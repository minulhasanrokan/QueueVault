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
                        <button onclick="get_activity_history_page('{{$single_data->division_name}}','Division Activity','{{route('activity.history',[$single_data->id, 'divisions'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->division_name}}','Division History','{{route('reference.division.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Division Name</td>
                    <td>{{$single_data->division_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Division Code</td>
                    <td>{{$single_data->division_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Division Title</td>
                    <td>{{$single_data->division_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Division Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->division_details!!}</p></td>
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

                all_response(response,'{{route(request()->route()->getName())}}','Active View Division');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>