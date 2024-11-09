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
                    <td width="150">Division Name</td>
                    <td width="500">{{$data->division_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">District Name</td>
                    <td width="500">{{$data->district_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Upazila Name</td>
                    <td width="500">{{$data->branch_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Assign Service</td>
                    <td width="500">{{App\Models\Common::get_assign_data($data->assign_service,'services','id','service_name','comma')}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch Name</td>
                    <td width="500">{{$data->branch_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch Code</td>
                    <td width="500">{{$data->branch_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch Title</td>
                    <td width="500">{{$data->branch_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch Status</td>
                    <td width="500">{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch AD Status</td>
                    <td width="500">{{$data->ad_status==1?'Yes':'No'}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Branch AD Code</td>
                    <td width="500">{{$data->ad_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Details</td>
                    <td width="500">{!!$data->branch_details!!}</td>
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