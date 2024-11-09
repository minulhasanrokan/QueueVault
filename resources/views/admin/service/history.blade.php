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
                    <td width="150">Service Name</td>
                    <td width="500">{{$data->service_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Service Code</td>
                    <td width="500">{{$data->service_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Service Letter</td>
                    <td width="500">{{$data->service_letter}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Service Starting Number</td>
                    <td width="500">{{$data->service_start_number}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Service Title</td>
                    <td width="500">{{$data->service_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Service Status</td>
                    <td width="500">{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Details</td>
                    <td width="500"><p>{!!$data->service_details!!}</p></td>
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