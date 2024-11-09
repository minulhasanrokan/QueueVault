<div class="container-fluid">
    <div class="card card-default px-1 py-1">
        <table id="history" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="150">Particular</th>
                    <th width="500">Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $data)
                <tr class="background_color_green" data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Approve Date</td>
                    <td width="500">{{format_datetime_view($data->verify_at)}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch Name</td>
                    <td width="500">{{$data->branch_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Floor Name</td>
                    <td width="500">{{$data->floor_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Assign Service</td>
                    <td width="500">{{App\Models\Common::get_assign_data($data->assign_service,'services','id','service_name','comma')}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Counter Name</td>
                    <td width="500">{{$data->counter_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Counter Code</td>
                    <td width="500">{{$data->counter_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Counter Title</td>
                    <td width="500">{{$data->counter_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Counter Status</td>
                    <td width="500">{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Details</td>
                    <td width="500"><p>{!!$data->counter_details!!}</p></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style type="text/css">
    
    #history tbody {
        display: block;
        max-height: 400px;
        overflow-y: auto;
    }

    #history tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
</style>