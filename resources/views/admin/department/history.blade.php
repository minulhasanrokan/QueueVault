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
                    <td width="150">Department Name</td>
                    <td width="500">{{$data->department_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Department Code</td>
                    <td width="500">{{$data->department_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Department Title</td>
                    <td width="500">{{$data->department_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Department Status</td>
                    <td width="500">{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Details</td>
                    <td width="500">{!!$data->department_details!!}</td>
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