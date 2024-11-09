<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">{!!$menu_data!!}</div>
        <div class="card">
            <div class="card-body">
                <table id="district_data_table" class="table table-bordered table-striped text-center" rules="all">
                    <thead>
                        <tr>
                            @foreach($grid_right as $right)
                            <th width="4"><i class="{{$right['r_icon']}}"></i></th>
                            @endforeach
                            <th width="100">Division Name</th>
                            <th width="100">Name</th>
                            <th width="5">Code</th>
                            <th width="50">OP-Status</th>
                            <th width="5">Status</th>
                            <th width="5">Message</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {

        $('#district_data_table').DataTable({
            "lengthMenu": [20, 50, 100,200,500],
            "pageLength": 20,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "scrollX": true, 
            "autoWidth": false,
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
            "columns": [

                @foreach($grid_right as $right)
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        var titleValue = full.district_name;

                        @if($right['r_action_name']=='view')
                        
                            return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                        @endif

                        @if($right['r_action_name']=='edit')
                            
                            if((full.process_status==2 || full.process_status==3 || full.process_status=='0' || full.process_status=='')){

                                if(full.process_status==3 || full.process_status==2)
                                {
                                    return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                                }
                                else if (full.actual_status!=4){

                                    return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                                }
                                else{

                                    return '';
                                }
                            }
                            else{

                                return '';
                            }
                        @endif

                        @if($right['r_action_name']=='delete')
                            
                            if((full.process_status==2 || full.process_status==3 || full.process_status=='0' || full.process_status=='')){
                                
                                if(full.process_status==3 || full.process_status==2)
                                {
                                    return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                                }
                                else if (full.actual_status!=3){

                                    return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                                }
                                else{

                                    return '';
                                }
                            }
                            else{

                                return '';
                            }
                        @endif

                        @if($right['r_action_name']=='sent_to_checker')
                            
                            if((full.process_status!=3 && full.process_status!=1 && full.actual_status!=5 && full.actual_status!=6)){
                                
                                return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                            }
                            else{

                                return '';
                            }
                        @endif

                        @if($right['r_action_name']=='verify')
                            
                            if(full.process_status==1){

                                return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                            }
                            else{
                                
                                return '';
                            }
                            
                        @endif

                        @if($right['r_action_name']=='active')
                            
                            if((full.process_status==3 || full.process_status==2 || full.actual_status==5) && full.status==0){
                                
                                return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                            }
                            else{

                                return '';
                            }
                        @endif

                        @if($right['r_action_name']=='deactive')
                            
                            if((full.process_status==3 || full.process_status==2 || full.actual_status==6) && full.status==1){
                                
                                return '<a href="#" title="{{$right['r_title']}}" onclick="get_new_page(\'{{route($right['r_route_name'])}}\',\'{{$right['r_title']}}\',\''+full.id+'\',\''+titleValue+'\',\'{{$right['popup_status']}}\',\'{{$right['width']}}\');" ><i class="nav-icon {{$right['r_icon']}}"></i></a>';
                            }
                            else{

                                return '';
                            }
                        @endif
                    }
                },
                @endforeach
                {
                    "data": "division_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "district_name",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "district_code",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data+'</div>';
                    }
                },
                {
                    "data": "actual_status",
                    "render": function(data, type, full, meta) {

                        var statusText = '';
                        
                        if (typeof process_status[full.process_status] !== 'undefined') {
                            
                            statusText = ' ' + process_status[full.process_status];
                        }

                        return '<div class="text-left">'+actual_status[data]+statusText+'</div>';
                    }
                },
                {
                    "data": "status",
                    "render": function(data, type, full, meta) {

                        return '<div class="text-left">'+data_status[data]+'</div>';
                    }
                },
                {
                    "data": "message",
                    "render": function(data, type, full, meta) {

                        if(data!=null){

                            return '<div class="text-left" onclick="return_message_history(\''+full.district_name+'\',\''+full.id+'\',\'districts\')">'+data+'</div>';
                        }
                        else{

                            return '';
                        }
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