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
                        <button onclick="get_activity_history_page('{{$single_data->upazila_name}}','Upazila Activity','{{route('activity.history',[$single_data->id, 'upazilas'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->upazila_name}}','Upazila History','{{route('reference.upazila.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Division Name</td>
                    <td>{{$single_data->division_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>District Name</td>
                    <td>{{$single_data->district_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Upazila Name</td>
                    <td>{{$single_data->upazila_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Upazila Code</td>
                    <td>{{$single_data->upazila_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Upazila Title</td>
                    <td>{{$single_data->upazila_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Upazila Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->upazila_details!!}</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>