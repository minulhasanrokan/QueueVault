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
                    <td width="150">User Group Name</td>
                    <td width="500">{{$data->group_name}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">User Group Code</td>
                    <td width="500">{{$data->group_code}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">User Group Title</td>
                    <td width="500">{{$data->group_title}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">User Group Status</td>
                    <td width="500">{{$data_status[$data->status]}}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">Details</td>
                    <td width="500">{!!$data->group_details!!}</td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">User Group Photo</td>
                    <td width="500"><a target="_blank" href="{{asset('uploads/user_group')}}/{{$data->group_logo!=''?$data->group_logo:'user_group_logo.png'}}"/><i class="fas fa-images"></i></a></td>
                </tr>
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td width="150">User Group Icon</td>
                    <td width="500"><a target="_blank" href="{{asset('uploads/user_group')}}/{{$data->group_icon!=''?$data->group_icon:'user_group_icon.png'}}"/><i class="fas fa-images"></i></a></td>
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