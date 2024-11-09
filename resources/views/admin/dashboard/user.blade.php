@php
    
    if(isset($dashboard_right['dashboard_right_arr']['r_route_name']['user_management']['user_management.user']) || $super_admin_status==1){

        $total_data = DB::table('users as a')
            ->select(DB::raw('COUNT(a.id) as total_data'))
            ->where('a.delete_status', 0)
            ->first();

        $route_name = '';
        $title = '';

        if($super_admin_status!=1){

            $route_name = route('user_management.user.view');

            $title  = $dashboard_right['dashboard_right_arr']['r_title']['user_management']['user_management.user']['view'];
        }
        else{

            foreach($dashboard_right['dashboard_right_arr']['r_action_name']['user_management']['user_management.user'] as $action_name){

                if($action_name!='add'){

                    $route_name = route($dashboard_right['dashboard_right_arr']['r_route_name']['user_management']['user_management.user'][$action_name]);

                    $title  = $dashboard_right['dashboard_right_arr']['r_title']['user_management']['user_management.user'][$action_name];

                    if($route_name!=''){

                        break;
                    }
                }
            }
        }
@endphp
    <div class="col-6 col-sm-3 col-md-2 px-1">
        <a href="#" onclick="get_new_page('{{$route_name}}','{{$title}}','','',0,0);" class="uppercase">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1"><i class="{{$dashboard_right['right_group_icon_arr']['user_management']['user_management.user']['c_icon']}}"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total User</span>
                    <span class="info-box-number">{{$total_data->total_data}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
@php
    }
@endphp