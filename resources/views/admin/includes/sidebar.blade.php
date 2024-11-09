@php
    
    $session_data = \App\Http\Controllers\CommonController::get_session_data();

    $user_name = $session_data['name'];
    $user_photo = $session_data['user_photo'];
    $admin_status = $session_data['super_admin_status'];
    $session_check_time = $session_data['session_check_time'];

    $user_right_data = array();

    //App\Models\Common::delete_cahce_data('menu_right:*');

    if($admin_status==1){

        $data = App\Models\Common::get_cache_value_by_key('menu_right:admin_righ_sidebar');

        if ($data['cache_status']==1){
            
            $user_right_data = $data['cache_data'];
        }
        else{
            
            $user_right_data = DB::table('right_details')
                ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon','right_details.popup_status','right_details.width')
                ->where('right_groups.status',1)
                ->where('right_categories.status',1)
                ->where('right_details.status',1)
                ->where('right_groups.delete_status',0)
                ->where('right_categories.delete_status',0)
                ->where('right_details.delete_status',0)
                ->orderBy('right_groups.short_order', 'ASC')
                ->orderBy('right_categories.short_order', 'ASC')
                ->orderBy('right_details.r_short_order', 'ASC')
                ->get()->toArray();

            $right_group_arr = array();
            $right_cat_arr = array();
            $right_arr = array();

            foreach($user_right_data as $data){

                $right_group_arr[$data->g_id]['g_id'] = $data->g_id;
                $right_group_arr[$data->g_id]['g_name'] = $data->g_name;
                $right_group_arr[$data->g_id]['g_action_name'] = $data->g_action_name;
                $right_group_arr[$data->g_id]['g_details'] = $data->g_details;
                $right_group_arr[$data->g_id]['g_title'] = $data->g_title;
                $right_group_arr[$data->g_id]['g_short_order'] = $data->g_short_order;
                $right_group_arr[$data->g_id]['g_icon'] = $data->g_icon;

                $right_cat_arr[$data->g_id][$data->c_id]['c_id'] = $data->c_id;
                $right_cat_arr[$data->g_id][$data->c_id]['c_name'] = $data->c_name;
                $right_cat_arr[$data->g_id][$data->c_id]['c_title'] = $data->c_title;
                $right_cat_arr[$data->g_id][$data->c_id]['c_action_name'] = $data->c_action_name;
                $right_cat_arr[$data->g_id][$data->c_id]['c_details'] = $data->c_details;
                $right_cat_arr[$data->g_id][$data->c_id]['c_short_order'] = $data->c_short_order;
                $right_cat_arr[$data->g_id][$data->c_id]['c_icon'] = $data->c_icon;

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_name'] = $data->r_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_title'] = $data->r_title;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_action_name'] = $data->r_action_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_route_name'] = $data->r_route_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_details'] = $data->r_details;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_short_order'] = $data->r_short_order;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_icon'] = $data->r_icon;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['popup_status'] = $data->popup_status;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['width'] = $data->width;
            }

            $right_data_arr['right_group_arr'] = $right_group_arr;
            $right_data_arr['right_cat_arr'] = $right_cat_arr;
            $right_data_arr['right_arr'] = $right_arr;

            App\Models\Common::put_cahce_value('menu_right:admin_righ_sidebar',$right_data_arr);

            $user_right_data = $right_data_arr;
        }
    }
    else{

        $data = App\Models\Common::get_cache_value_by_key('menu_right:user_right_sidebar'.$session_data['id']);

        if ($data['cache_status']==1){
            
            $user_right_data = $data['cache_data'];
        }
        else{

            $user_right_data = DB::table('right_details')
                ->join('user_rights', 'right_details.id', '=', 'user_rights.r_id')
                ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon','right_details.popup_status','right_details.width')
                ->where('user_rights.user_id',$session_data['id'])
                ->where('right_groups.status',1)
                ->where('right_categories.status',1)
                ->where('right_details.status',1)
                ->where('right_groups.delete_status',0)
                ->where('right_categories.delete_status',0)
                ->where('right_details.delete_status',0)
                ->orderBy('right_groups.short_order', 'ASC')
                ->orderBy('right_categories.short_order', 'ASC')
                ->orderBy('right_details.r_short_order', 'ASC')
                ->get()->toArray();

            $right_group_arr = array();
            $right_cat_arr = array();
            $right_arr = array();

            foreach($user_right_data as $data){

                $right_group_arr[$data->g_id]['g_id'] = $data->g_id;
                $right_group_arr[$data->g_id]['g_name'] = $data->g_name;
                $right_group_arr[$data->g_id]['g_action_name'] = $data->g_action_name;
                $right_group_arr[$data->g_id]['g_details'] = $data->g_details;
                $right_group_arr[$data->g_id]['g_title'] = $data->g_title;
                $right_group_arr[$data->g_id]['g_short_order'] = $data->g_short_order;
                $right_group_arr[$data->g_id]['g_icon'] = $data->g_icon;

                $right_cat_arr[$data->g_id][$data->c_id]['c_id'] = $data->c_id;
                $right_cat_arr[$data->g_id][$data->c_id]['c_name'] = $data->c_name;
                $right_cat_arr[$data->g_id][$data->c_id]['c_title'] = $data->c_title;
                $right_cat_arr[$data->g_id][$data->c_id]['c_action_name'] = $data->c_action_name;
                $right_cat_arr[$data->g_id][$data->c_id]['c_details'] = $data->c_details;
                $right_cat_arr[$data->g_id][$data->c_id]['c_short_order'] = $data->c_short_order;
                $right_cat_arr[$data->g_id][$data->c_id]['c_icon'] = $data->c_icon;

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_name'] = $data->r_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_title'] = $data->r_title;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_action_name'] = $data->r_action_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_route_name'] = $data->r_route_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_details'] = $data->r_details;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_short_order'] = $data->r_short_order;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_icon'] = $data->r_icon;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['popup_status'] = $data->popup_status;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['width'] = $data->width;
            }

            $right_data_arr['right_group_arr'] = $right_group_arr;
            $right_data_arr['right_cat_arr'] = $right_cat_arr;
            $right_data_arr['right_arr'] = $right_arr;

            App\Models\Common::put_cahce_value('menu_right:user_right_sidebar'.$session_data['id'],$right_data_arr);

            $user_right_data = $right_data_arr;
        }
    }

    $right_group_arr = $user_right_data['right_group_arr'];
    $right_cat_arr = $user_right_data['right_cat_arr'];
    $right_arr = $user_right_data['right_arr'];

@endphp

<aside class="main-sidebar sidebar-light-primary elevation-4">
    <div class="sidebar">
        <div class="d-flex">
                <a href="{{url('')}}" class="brand-link">
                <img src="{{asset('uploads/system')}}/{{$system_data['system_logo']}}" alt="{{$system_data['system_title']}}" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">{{$system_data['system_name']}}</span>
            </a>
        </div>
        <!--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('uploads/user')}}/{{$user_photo!=''?$user_photo:'user.png'}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">{{$user_name!=''?$user_name:'User'}}</div>
        </div>-->
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @php
                    $group_menu_sl= 0;

                    foreach($right_group_arr as $group_data){

                        $group_menu_sl++;
                @endphp
                    <input type="hidden" id="menu_group_open_status{{$group_menu_sl}}" name="menu_group_open_status{{$group_menu_sl}}" value="0">
                    <li class="nav-item" id="menu_group{{$group_menu_sl}}" onclick="group_menu_open('{{$group_menu_sl}}');">
                        <a href="" class="nav-link" id="menu_group_link{{$group_menu_sl}}">
                            <i class="nav-icon {{$group_data['g_icon']}}"></i>
                            <p>{{$group_data['g_name']}}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @php
                                $group_cat_sl= 0;

                                foreach($right_cat_arr[$group_data['g_id']] as $cat_data){

                                    $group_cat_sl++;
                            @endphp
                                <input type="hidden" id="menu_cat_open_status{{$group_menu_sl}}{{$group_cat_sl}}" name="menu_cat_open_status{{$group_menu_sl}}{{$group_cat_sl}}" value="0">
                                <li class="nav-item" id="menu_cat{{$group_menu_sl}}{{$group_cat_sl}}" onclick="group_menu_cat_open(event,'{{$group_menu_sl}}','{{$group_cat_sl}}');">
                                    <a href="" class="nav-link" id="menu_cat_link{{$group_menu_sl}}{{$group_cat_sl}}">
                                        <i class="nav-icon {{$cat_data['c_icon']}}"></i>
                                        <p>{{$cat_data['c_name']}}<i class="fas fa-angle-left right"></i></p>
                                     </a>
                                    <ul class="nav nav-treeview">
                                        @php
                                            $group_link_sl= 0;

                                            foreach($right_arr[$group_data['g_id']][$cat_data['c_id']] as $right_data){

                                                $group_link_sl++;
                                        @endphp
                                            <input type="hidden" id="menu_link_open_status{{$group_menu_sl}}{{$group_cat_sl}}{{$group_link_sl}}" name="menu_link_open_status{{$group_menu_sl}}{{$group_cat_sl}}{{$group_link_sl}}" value="0">
                                            <li class="nav-item" id="menu_link{{$group_menu_sl}}{{$group_cat_sl}}{{$group_link_sl}}">
                                                <a href="#" onclick="get_new_page('{{route($right_data['r_route_name'])}}','{{$right_data['r_title']}}','','','{{$right_data['popup_status']}}','{{$right_data['width']}}'); menu_open(event,'{{$group_menu_sl}}','{{$group_cat_sl}}','{{$group_link_sl}}');" id="menu_id{{$group_menu_sl}}{{$group_cat_sl}}{{$group_link_sl}}" class="nav-link">
                                                    <i class="nav-icon {{$right_data['r_icon']}}"></i>
                                                    <p>{{$right_data['r_name']}}</p>
                                                </a>
                                            </li>
                                        @php
                                            }
                                        @endphp

                                        <input type="hidden" id="total_menu{{$group_menu_sl}}{{$group_cat_sl}}" name="total_menu{{$group_menu_sl}}{{$group_cat_sl}}" value="{{$group_link_sl}}">

                                    </ul>
                                </li>
                            @php
                                }

                                @endphp

                                <input type="hidden" id="total_sub_menu{{$group_menu_sl}}" name="total_sub_menu{{$group_menu_sl}}" value="{{$group_cat_sl}}">

                                @php
                            @endphp
                        </ul>
                    </li>
                @php
                    }
                @endphp
                <li class="nav-item">
                    <a href="{{route('lock')}}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-lock"></i>
                        <p>Lock</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('logout')}}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<script type="text/javascript">

    const base_url = '{{url('/')}}';

    function get_new_page(url,title,data,name,popup_stats,width) {

        var name_details = '';

        if(name!=''){

            name_details +=" - "+name;
        }

        document.title = title + name_details+ ' | '+'{{$system_data['system_name']}}';
        var title1 = title +name_details+ ' | '+'{{$system_data['system_name']}}';
        //window.history.pushState({}, title1, url+'/'+data);

        showLoading();

        $.ajax({
            async: false,
            type: "GET",
            url: url+"/"+data,
            success: function (response){

                hideLoading(1000);

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    if(popup_stats==1 || data!=''){

                        $("#staticBackdropLabel").html(title + name_details);
                        $("#modal_body").html(response);
                        $('#staticBackdrop #modal_dialog').css('max-width', width+'px');
                        $('#modal_body #menu_data').hide();
                        $('#staticBackdrop').modal('show');

                        $('#staticBackdrop').draggable({
                            handle: ".modal-header"
                        });
                    }
                    else{

                        $("#staticBackdropLabel").html('');
                        $("#modal_body").html('');
                        $('#modal_body #menu_data').hide();
                        $('#staticBackdrop').modal('hide');

                        $("#page_content").html(response);
                    }
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    function check_session_status(){

        $.ajax({
            async: false,
            type: "GET",
            url: "{{route('check.session')}}",
            contentType: "application/x-www-form-urlencoded",
            dataType: "text",
            success: function (response) { 

                var reponse=trim(response);

                if(reponse=='Session Expire'){

                    alert(reponse);

                    location.replace(base_url+'/logout');
                }
                else if(reponse=='lock'){

                    alert(reponse);

                    location.replace(base_url+'/lock');
                }
                else{

                    $('meta[name="csrf-token"]').attr('content', reponse);
                    $('input[name="_token"]').attr('value', response);
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    check_session_status();

    setInterval(check_session_status, {{$session_check_time}});

    var update_session_status = 0;

    function update_session(event){

        if(update_session_status==0){

            update_session_status = 1;

            $.ajax({
                async: false,
                type: "GET",
                url: "{{route('update.session')}}",
                contentType: "application/x-www-form-urlencoded",
                dataType: "text",
                success: function (response) {

                    var reponse=trim(response);

                    if(reponse=='Session Expire'){

                        alert(reponse);

                        location.replace(base_url+'/logout');
                    }
                    else if(reponse=='lock'){

                        alert(reponse);

                        location.replace(base_url+'/lock');
                    }
                    else{

                        $('meta[name="csrf-token"]').attr('content', reponse);
                        $('input[name="_token"]').attr('value', response);
                    }
                },
                error: function(xhr, status, error) {

                    response_error_alert();
                }
            });

            setTimeout(function () {update_session_status = 0;}, {{$session_check_time}});
        }
    }

    document.addEventListener('mousemove', update_session);

    document.addEventListener('DOMContentLoaded', function () {

        document.addEventListener('keydown', function (event) {

            update_session(event);
        });

        document.addEventListener('click', function (event) {

            update_session(event);

        });
    });

    function check_duplicate_value(field_name,table_name,value,data_id=0){

        input_field_name = field_name;

        var value = trim(value);

        if(value==''){

            return true;
        }
 
        if(value!=''){

            $.ajax({
                async: false,
                url: "{{route('get.duplicate.value')}}",
                type: "POST",
                data: {
                    field_name: field_name,
                    table_name: table_name,
                    value: value,
                    data_id: data_id
                },
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function (response) {

                    if(response=='Session Expire' || response=='Right Not Found'){

                        alert(response);

                        location.replace(base_url+'/logout');
                    }
                    else if(response=='lock'){

                        alert(response);

                        location.replace(base_url+'/lock');
                    }
                    else{

                        $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                        $('input[name="_token"]').attr('value', response.csrf_token);

                        if(response.status>0){

                            var field_name_arr = input_field_name.split("_");

                            $("#"+input_field_name).val('');

                            var value1 = '';
                            var value2 = '';

                            if (typeof field_name_arr[0] !== 'undefined'){

                                value1 = 'Duplicate ' + capitalizeFirstLetter(field_name_arr[0]);
                            }

                            if (typeof field_name_arr[1] !== 'undefined'){
                                
                                value2 = ' ' + capitalizeFirstLetter(field_name_arr[1]);
                            }

                            alert(value1+value2);

                            return false;
                        }
                        else{

                            return true;
                        }
                    }
                },
                error: function(xhr, status, error) {

                    response_error_alert();
                }
            });
        }
    }

    function check_combain_duplicate_value(field_name,table_name,data_id=0,alert_field){

        var field_arr = field_name.split('*');

        var field_values = {};

        field_arr.forEach(function(field) {
            
            var field_value = document.getElementById(field).value;
            
            field_values[field] = field_value;
        });

        var return_status = true;

        $.ajax({
            async: false,
            url: "{{route('get.combain.duplicate.value')}}",
            type: "POST",
            data: {
                table_name: table_name,
                field_values: field_values,
                data_id: data_id
            },
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function (response) {

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    $('meta[name="csrf-token"]').attr('content', response.csrf_token);
                    $('input[name="_token"]').attr('value', response.csrf_token);

                    if(response.status>0){

                        var field_name_arr = alert_field.split("_");

                        $("#"+alert_field).val('');

                        var value1 = '';
                        var value2 = '';

                        if (typeof field_name_arr[0] !== 'undefined'){

                            value1 = 'Duplicate ' + capitalizeFirstLetter(field_name_arr[0]);
                        }

                        if (typeof field_name_arr[1] !== 'undefined'){
                            
                            value2 = ' ' + capitalizeFirstLetter(field_name_arr[1]);
                        }

                        alert(value1+value2);

                        return_status = false;
                    }
                    else{

                        return_status = true;
                    }
                }
            },
            error: function(xhr, status, error) {

                return_status = false;

                response_error_alert();
            }
        });

        return return_status;
        
    }

    function return_message_history(name,id,table_name) {

        document.title = name+ ' | Message History | '+'{{$system_data['system_name']}}';

        showLoading();

        $.ajax({
            async: false,
            type: "GET",
            url: "{{route('return.message.history')}}/"+id+"/"+table_name,
            success: function (response){

                hideLoading(1000);

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    $("#staticBackdropLabel").html('Message History - ' + name);
                    $("#modal_body").html(response);
                    $('#staticBackdrop #modal_dialog').css('max-width', '1000px');
                    $('#staticBackdrop').modal('show');

                    $('#staticBackdrop').draggable({
                        handle: ".modal-header"
                    });
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    function get_right_details_page(name,title_text,url) {

        document.title = name+ ' | '+title_text+' | '+'{{$system_data['system_name']}}';

        showLoading();

        $.ajax({
            async: false,
            type: "GET",
            url: url,
            success: function (response){

                hideLoading(1000);

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    $("#right_view_modal_title").html(title_text+' - ' + name);
                    $("#right_view_modal_body").html(response);
                    $('#right_view_modal #right_modal_dialog').css('max-width', '1000px');
                    $('#right_view_modal').modal('show');

                    /*$('#right_view_modal').draggable({
                        handle: ".modal-header"
                    });*/
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    function get_activity_history_page(name,title_text,url) {

        document.title = name+ ' | '+title_text+' | '+'{{$system_data['system_name']}}';

        showLoading();

        $.ajax({
            async: false,
            type: "GET",
            url: url,
            success: function (response){

                hideLoading(1000);

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    $("#activity_history_modal_title").html(title_text+' - ' + name);
                    $("#activity_history_modal_body").html(response);
                    $('#activity_history_modal #activity_history_modal_dialog').css('max-width', '700px');
                    $('#activity_history_modal').modal('show');

                    $('#activity_history_modal').draggable({
                        handle: ".modal-header"
                    });
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    function get_history_page(name,title_text,url) {

        document.title = name+ ' | '+title_text+' | '+'{{$system_data['system_name']}}';

        showLoading();

        $.ajax({
            async: false,
            type: "GET",
            url: url,
            success: function (response){

                hideLoading(1000);

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    $("#history_modal_title").html(title_text+' - ' + name);
                    $("#history_modal_body").html(response);
                    $('#history_modal #history_modal_dialog').css('max-width', '900px');
                    $('#history_modal').modal('show');

                    $('#history_modal').draggable({
                        handle: ".modal-header"
                    });
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    function group_menu_open(id){

        var total_group = '{{$group_menu_sl}}';

        var menu_group_open_status = $("#menu_group_open_status"+id).val();

        for(var i=1; i<=total_group; i++){

            if (i!=id){

                $("#menu_group"+i).removeClass("nav-item menu-is-opening menu-open").addClass("nav-item");

                $("#menu_group_link"+i).removeClass("menu-group-bg-color nav-link").addClass("nav-link");

                var menu_list = $("#menu_group"+i+" ul");

                menu_list.css("display", "none");

                var total_sub_menu = $("#total_sub_menu"+i).val();

                for(var j=1; j<=total_sub_menu; j++){

                    $("#menu_cat"+i+j).removeClass("nav-item menu-is-opening menu-open").addClass("nav-item");
                    $("#menu_cat_link"+i+j).removeClass("sub-menu-bg-color nav-link").addClass("nav-link");

                    var total_menu = $("#total_menu"+i+j).val();

                    for(var k=1; k<=total_menu; k++){

                        $("#menu_id"+i+j+k).removeClass("menu-bg-color");
                        $("#menu_link_open_status"+i+j+k).val(0);
                    }
                }

                $("#menu_group_open_status"+i).val(0);
            }
            else{

                $("#menu_group_link"+i).removeClass("nav-link").addClass("menu-group-bg-color nav-link");

                $("#menu_group_open_status"+i).val(1);
            }
        }
    }

    function group_menu_cat_open(event,group_id,cat_id){

        event.preventDefault();

        var total_sub_menu = $("#total_sub_menu"+group_id).val();

        var menu_cat_open_status = $("#menu_cat_open_status"+group_id+cat_id).val();

        for(var i=1; i<=total_sub_menu; i++){

            if (i!=cat_id){

                $("#menu_cat"+group_id+i).removeClass("nav-item menu-is-opening menu-open").addClass("nav-item");

                $("#menu_cat_link"+group_id+i).removeClass("sub-menu-bg-color nav-link").addClass("nav-link");

                var menu_list = $("#menu_cat"+group_id+i+" ul");

                menu_list.css("display", "none");

                $("#menu_cat_open_status"+group_id+i).val(0);

                var total_menu = $("#total_menu"+group_id+i).val();

                for(var k=1; k<=total_menu; k++){

                    $("#menu_id"+group_id+i+k).removeClass("menu-bg-color");
                    $("#menu_link_open_status"+group_id+i+k).val(0);
                }
            }
            else{

                $("#menu_cat_link"+group_id+i).removeClass("nav-link").addClass("sub-menu-bg-color nav-link");

                $("#menu_cat_open_status"+group_id+i).val(1);

                var total_menu = $("#total_menu"+group_id+i).val();

                for(var k=1; k<=total_menu; k++){

                    var menu_open_status = $("#menu_link_open_status"+group_id+i+k).val();

                    if(menu_open_status==0){

                        $("#menu_id"+group_id+i+k).removeClass("menu-bg-color");
                    }
                }
            }
        }
    }

    function menu_open(event,group_id,cat_id,menu_id){

        event.preventDefault();

        var total_menu = $("#total_menu"+group_id+cat_id).val();

        var menu_open_status = $("#menu_link_open_status"+group_id+cat_id+menu_id).val();

        for(var i=1; i<=total_menu; i++){

            if (i!=menu_id){

                $("#menu_id"+group_id+cat_id+i).removeClass("menu-bg-color");

                $("#menu_link_open_status"+group_id+cat_id+i).val(0);
            }
            else{

                $("#menu_id"+group_id+cat_id+i).addClass("menu-bg-color");

                $("#menu_link_open_status"+group_id+cat_id+i).val(1);
            }
        }
    }

    function blank_drop_down(id,container,title,drop_down_type,disabale_status,tab_index){

        var data = '';

        if(drop_down_type==1){

            data ='<select tabindex="'+tab_index+'" name="'+id+'" class="bootstrap-select form-control" id="'+id+'" title="'+title+'" multiple="multiple" data-live-search="true">';
        }
        else{

            data ='<select tabindex="'+tab_index+'" name="'+id+'" class="bootstrap-select form-control" id="'+id+'" title="'+title+'" data-live-search="true">';
        }

        data +='<option value="">'+title+'</option></select>';

        document.getElementById(container).innerHTML = data;

        $('#'+id).selectpicker();

        if(disabale_status==1){

            $("#"+id).attr('disabled','disabled ');
        }
    }

    function load_drop_down(table_name, field_name, id, container, title, drop_down_type, selected_value, disabale_status,tab_index,on_change,other_field,other_value,not_equal_field,not_equal_value){

        var value = 0;

        if(selected_value!=''){

            value = selected_value;
        }

        if(other_field=='')
        {
            other_field = 0;
        }

        if(other_value=='')
        {
            other_value = 0; 
        }

        if(not_equal_field=='')
        {
            not_equal_field = 0;
        }

        if(other_value=='')
        {
            not_equal_value = 0; 
        }

        $.ajax({
            async: false,
            type: "GET",
            url: "{{route('get.dropdown')}}/"+table_name+'/'+field_name+'/'+value+'/'+other_field+'/'+other_value+'/'+not_equal_field+'/'+not_equal_value,
            success: function (response){

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    var on_change_data = on_change;

                    if(on_change!=''){

                        var on_change_data = 'onchange="'+on_change+'"';
                    }

                    var data = '';

                    if(drop_down_type==1){

                        data ='<select tabindex="'+tab_index+'" '+on_change_data+' name="'+id+'" class="bootstrap-select form-control" id="'+id+'" title="'+title+'" multiple="multiple" data-live-search="true">';
                    }
                    else{

                        data ='<select tabindex="'+tab_index+'" '+on_change_data+' name="'+id+'" class="bootstrap-select form-control" id="'+id+'" title="'+title+'" data-live-search="true">';
                    }

                    data +='<option value="">'+title+'</option>';

                    var data_length = Object.keys(response).length;

                    var field_name_arr = field_name.split(',');

                    for(var i=0; i<data_length; i++){

                        data +='<option value="'+response[i][field_name_arr[0]]+'">'+response[i][field_name_arr[1]]+'</option>';
                    }

                    data +='</select>';

                    document.getElementById(container).innerHTML = data;

                    var hidden_field_value = '';

                    if (typeof field_name_arr[2] !== 'undefined') {

                        for(var i=0; i<data_length; i++){

                            hidden_field_value +='<input type="hidden" name="hidden_field-'+field_name_arr[2]+'-'+response[i][field_name_arr[0]]+'" id="hidden_field-'+field_name_arr[2]+'-'+response[i][field_name_arr[0]]+'" value="'+response[i][field_name_arr[2]]+'" />';
                        }
                    }

                    document.getElementById(container).innerHTML += hidden_field_value;

                    $('#'+id).selectpicker();

                    if(drop_down_type==1){

                        if(selected_value!=''){

                            var data_arr = selected_value.split(",");

                            $("#" + id).val(data_arr).selectpicker('refresh');
                        }
                    }
                    else{

                        if(selected_value!=''){

                            $("#" + id).val(selected_value).selectpicker('refresh');
                        }
                    }

                    if(disabale_status==1){

                        $("#"+id).attr('disabled','disabled ');
                    }
                }
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    function make_data_table(table_id){

        $('#'+table_id).DataTable({
            "lengthMenu": [20, 50, 100, 200, 500],
            "pageLength": 20,
            "serverSide": false,
            "responsive": false,
            "colReorder": false,
            "scrollX": true, 
            "autoWidth": true,
            "scrollCollapse": false,
            "searching": false,
        });
    }

    function data_table_height(table_id,height){

        $('#'+table_id+'_wrapper .dataTables_scrollBody').css('height', height+'px');
    }

    @if(Session::has('message'))
        var type = "{{ Session::get('alert_type','info') }}"
        switch(type){
            case 'info':
            toastr.info(" {{ Session::get('message') }} ");
            break;

            case 'success':
            toastr.success(" {{ Session::get('message') }} ");
            break;

            case 'warning':
            toastr.warning(" {{ Session::get('message') }} ");
            break;

            case 'error':
            toastr.error(" {{ Session::get('message') }} ");
            break; 
        }
    @endif

    var data_status = {
        @foreach($data_status as $key=>$status)
            {{$key}}: '{{$status}}',
        @endforeach
    };

    var actual_status = {
        @foreach($actual_status as $key=>$status)
            {{$key}}: '{{$status}}',
        @endforeach
    };

    var process_status = {
        @foreach($process_status as $key=>$status)
            {{$key}}: '{{$status}}',
        @endforeach
    };

    var yes_no_status = {
        0: 'No',
        1: 'Yes',
    };
    
</script>