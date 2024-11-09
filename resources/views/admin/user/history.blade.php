<div class="container-fluid">
    <div class="card card-default px-1 py-1">
        <table id="history" class="table table-bordered table-hover table-head-fixed">
            <thead>
                <tr>
                    <th>Particular</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $data)
                <tr class="background_color_green" data-widget="expandable-table" aria-expanded="false">
                    <td >Approve Date</td>
                    <td >{{format_datetime_view($data->verify_at)}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Name</td>
                    <td>{{$data->name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User ID</td>
                    <td>{{$data->user_id}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Group</td>
                    <td>{{$data->group_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Branch</td>
                    <td>{{$data->branch_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Department</td>
                    <td>{{$data->department_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Assign Department</td>
                    <td>{{App\Models\Common::get_assign_data($data->assign_department,'departments','id','department_name','comma')}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Designation</td>
                    <td>{{$data->designation_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Address</td>
                    <td>{{$data->address}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Modile</td>
                    <td>{{$data->mobile}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User E-mail</td>
                    <td>{{$data->email}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>User Status</td>
                    <td>{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Details</td>
                    <td><p>{!!$data->details!!}</p></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>Employee/User Photo</td>
                    <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user')}}/{{$data->user_photo!=''?$data->user_photo:'user.png'}}"/></td>
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