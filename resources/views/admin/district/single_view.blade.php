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
                        <button onclick="get_activity_history_page('{{$single_data->district_name}}','District Activity','{{route('activity.history',[$single_data->id, 'districts'])}}')" class="btn btn-primary float-right ml-1">Activity</button>
                        <button onclick="get_history_page('{{$single_data->district_name}}','District History','{{route('reference.district.history',$single_data->id)}}')" class="btn btn-primary float-right ml-1">History</button>
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
                    <td>District Code</td>
                    <td>{{$single_data->district_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>District Title</td>
                    <td>{{$single_data->district_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>District Status</td>
                    <td>{{$data_status[$single_data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$single_data->district_details!!}</p></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>