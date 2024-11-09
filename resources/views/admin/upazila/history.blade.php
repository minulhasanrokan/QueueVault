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
                    <td width="500">{{$data->upazila_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Upazila Code</td>
                    <td width="500">{{$data->upazila_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Upazila Title</td>
                    <td width="500">{{$data->upazila_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Upazila Status</td>
                    <td width="500">{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Details</td>
                    <td width="500">{!!$data->upazila_details!!}</td>
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