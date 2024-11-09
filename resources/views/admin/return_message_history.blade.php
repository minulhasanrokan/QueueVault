<div class="container-fluid">
    <div class="card card-default">
        <table id="return_reason_table" class="table-bordered table-hover">
            <thead>
                <tr>
                    <th width="200">Message Type</th>
                    <th width="300">Message</th>
                    <th width="200">Add By</th>
                    <th width="150">Add Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($message_data as $data)
                <tr>
                    <td width="200">{{isset($actual_status[$data->actual_status])?$actual_status[$data->actual_status]:''}} {{isset($process_status[$data->process_status])?$process_status[$data->process_status]:''}}</td>
                    <td width="300">{{$data->message}}</td>
                    <td width="200">{{$data->user_name}}</td>
                    <td width="150">{{format_datetime_view($data->created_at)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style type="text/css">
    
    #return_reason_table tbody {
        display: block;
        max-height: 300px;
        overflow-y: auto;
    }

    #return_reason_table tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
</style>