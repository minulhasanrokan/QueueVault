<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">{!!$menu_data!!}</div>
        <div class="card">
            <div class="card-body">
                <table id="counter_data_table" class="table table-bordered table-striped text-center" rules="all">
                    <thead>
                        <tr>
                            @foreach($grid_right as $right)
                            <th width="10"><i class="{{$right['r_icon']}}"></i></th>
                            @endforeach
                            <th width="6">Branch</th>
                            <th width="6">Service</th>
                            <th width="6">FLoor</th>
                            <th width="6">Counter</th>
                            <th width="6">Token</th>
                            <th width="6">Reference No</th>
                            <th width="6">Call Status</th>
                            <th width="6">VIP Status</th>
                            <th width="6">Call Date</th>
                            <th width="6">Start Time</th>
                            <th width="6">End Time</th>
                            <th width="6">Waiting Time</th>
                            <th width="6">Counter Time</th>
                            <th width="6">Total Time</th>
                            <th width="6">Total Call</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {

        $('#counter_data_table').DataTable({
            "lengthMenu": [20, 50, 100,200,500],
            "pageLength": 20,
            "serverSide": true,
            "responsive": false,
            "colReorder": true,
            "scrollX": true, 
            "autoWidth": true,
            "scrollY": "400px",
            "scrollCollapse": true,
            "ajax":{
                "url": "{{route(request()->route()->getName())}}",
                "type": "POST",
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                    showLoading();
                },
                "error": function(xhr, status, error) {

                    response_error_alert();
                },
                "dataSrc": function ( json ) {

                    return grid_response(json);
                }
            },
            "order": [
                [0, 'desc']
            ],
            "fnRowCallback": function(nRow, data, iDisplayIndex, iDisplayIndexFull) {

                if(data.not_show_status==0 && data.served_status==0){
                    $('td', nRow).css('background-color', 'red');
                }
                else if(data.not_show_status==1){
                    $('td', nRow).css('background-color', 'yellow');
                }
            },
            "columns": [

                @foreach($grid_right as $right)
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        var titleValue = full.token_number;

                        @if($right['r_action_name']=='my_call_list')
                        
                            return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                        @endif
                    }
                },
                @endforeach
                {
                    "data": "branch_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "service_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "floor_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "counter_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "token_number",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "reference_no",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "call_status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+yes_no_status[data]+'</div>';
                    }
                },
                {
                    "data": "vip_status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+yes_no_status[data]+'</div>';
                    }
                },
                {
                    "data": "called_date",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+formatDate(data)+'</div>';
                        }
                    }
                },
                {
                    "data": "started_at",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+formatDatetime(data)+'</div>';
                        }
                    }
                },
                {
                    "data": "complete_at",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+formatDatetime(data)+'</div>';
                        }
                    }
                },
                {
                    "data": "waiting_time",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+data+'</div>';
                        }
                    }
                },
                {
                    "data": "counter_time",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+data+'</div>';
                        }
                    }
                },
                {
                    "data": "turn_around_time",
                    "render": function(data, type, full, meta) {

                        if(data==null){

                            return '';
                        }
                        else{

                            return '<div class="text-left">'+data+'</div>';
                        }
                    }
                },
                {
                    "data": "current_call_status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [ 
                        @php

                            $total = count($grid_right);

                            for($i=0; $i<$total; $i++){
                                echo $i.',';
                            }

                        @endphp
                    ],
                    searchable: false,
                    orderable: false
                }
            ]
        });
    });

</script>