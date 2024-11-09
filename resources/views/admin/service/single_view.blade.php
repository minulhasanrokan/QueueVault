<div class="container-fluid">
    <div class="card card-default px-1 py-1">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th width="200">Particular</th>
                    <th>
                        Details
                        <button onclick="get_activity_history_page('{{$single_data->service_name}}','Service Activity','{{route('activity.history',[$single_data->id, 'services'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->service_name}}','Service History','{{route('reference.service.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Service Name</td>
                    <td>{{$single_data->service_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Service Code</td>
                    <td>{{$single_data->service_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Service Letter</td>
                    <td>{{$single_data->service_letter}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Service Starting Number</td>
                    <td>{{$single_data->service_start_number}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Service Title</td>
                    <td>{{$single_data->service_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Service Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->service_details!!}</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>