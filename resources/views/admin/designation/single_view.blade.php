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
                        <button onclick="get_activity_history_page('{{$single_data->designation_name}}','Designation Activity','{{route('activity.history',[$single_data->id, 'designations'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->designation_name}}','Designation History','{{route('reference.designation.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Designation Name</td>
                    <td>{{$single_data->designation_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Designation Code</td>
                    <td>{{$single_data->designation_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Designation Title</td>
                    <td>{{$single_data->designation_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Designation Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->designation_details!!}</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>