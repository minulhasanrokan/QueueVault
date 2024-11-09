<div class="container-fluid">
    <div class="card card-default">
        <table id="activity_history_table" class=" table-bordered table-hover">
            <thead>
                <tr>
                    <th width="300">Activity Type</th>
                    <th width="220">Add By</th>
                    <th width="170">Add Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history_data as $data)
                <tr>
                    <td width="300">{{isset($actual_status[$data->actual_status])?$actual_status[$data->actual_status]:''}} {{isset($process_status[$data->process_status])?$process_status[$data->process_status]:''}}</td>
                    <td width="220">{{$data->user_name}}</td>
                    <td width="170">{{format_datetime_view($data->created_at)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<style type="text/css">
    
    #activity_history_table tbody {
        display: block;
        max-height: 300px;
        overflow-y: auto;
    }

    #activity_history_table tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
</style>