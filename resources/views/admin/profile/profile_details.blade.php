<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Profile Deatils</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="20%">Particular</th>
                        <th width="80%">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Name</td>
                        <td>{{$single_data->name}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User ID</td>
                        <td>{{$single_data->user_id}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Group</td>
                        <td>{{$single_data->group_name}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Branch</td>
                        <td>{{$single_data->branch_name}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Department</td>
                        <td>{{$single_data->department_name}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Assign Department</td>
                        <td>{{App\Models\Common::get_assign_data($single_data->assign_department,'departments','id','department_name','comma')}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Designation</td>
                        <td>{{$single_data->designation_name}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Address</td>
                        <td>{{$single_data->address}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Modile</td>
                        <td>{{$single_data->mobile}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User E-mail</td>
                        <td>{{$single_data->email}}</td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Details</td>
                        <td><p>{!!$single_data->details!!}</p></td>
                    </tr>
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>Employee/User Photo</td>
                        <td><img class="rounded avatar-lg" width="80" height="80" src="{{asset('uploads/user')}}/{{$single_data->user_photo!=''?$single_data->user_photo:'user.png'}}"/></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>