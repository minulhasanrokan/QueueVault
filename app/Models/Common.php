<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use DB;

class Common extends Model
{
    protected $guarded = [];

    use HasFactory;

    public static function insert_user_log($user_id){

        try {

            $data_arr['user_id'] = $user_id;
            $data_arr['ip_address'] = request()->ip();
            $data_arr['user_agent'] = request()->header('User-Agent');
            $data_arr['log_in_time'] = now();
            $data_arr['last_active_time'] = now();
            $data_arr['log_out_time'] = now();
            $data_arr['add_by'] = $user_id;

            $updateData = [
                'log_out_status' => 1,
                'force_status' => 1,
            ];

            DB::table('user_log_histories')
                ->where('user_id', $user_id)
                ->where('log_out_status', 0)
                ->where('force_status', 0)
                ->update($updateData);

            return DB::table('user_log_histories')->insertGetId($data_arr);

        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public static function get_cache_value_by_key($key){

        $data_arr['cache_status'] = 0;

        if (Redis::exists($key)) {

            $data_arr['cache_data'] = unserialize(Redis::get($key));

            $data_arr['cache_status'] = 0;
        }

        return $data_arr;
    }

    public static function put_cahce_value($key,$value,$time=null){

        Redis::set($key, serialize($value));
    }

    public static function delete_cahce_data($pattern='*'){

        if($pattern=='' || $pattern==null){

            Redis::flushall();
        }
        else{

            $keys = Redis::keys($pattern);

            if (!empty($keys)) {
                
                Redis::del($keys);
            }
        }
    }

    public static function get_system_setting_data(){

        //Common::delete_cahce_data('system_settings');

        $data = Common::get_cache_value_by_key('system_settings');

        if ($data['cache_status']==1){
            
            return $data['cache_data'];
        }
        else{

            $data = DB::table('system_settings')
                ->select('system_name','system_title','system_icon','system_logo','system_version','system_copy_right','system_bg_image','system_slogan','system_email','system_mobile','system_address','system_details','password_validity_days','password_min_length','default_password_type','userid_min_length','password_max_length','userid_max_length','session_time','session_check_time')
                ->where('delete_status',0)
                ->where('status',1)
                ->first();

            if(!empty($data)){

                $system_data['system_name']=$data->system_name;
                $system_data['system_title']=$data->system_title;
                $system_data['system_icon']=$data->system_icon;
                $system_data['system_logo']=$data->system_logo;
                $system_data['system_version']=$data->system_version;
                $system_data['system_copy_right']=$data->system_copy_right;
                $system_data['system_bg_image']=$data->system_bg_image;
                $system_data['system_slogan']=$data->system_slogan;
                $system_data['system_email']=$data->system_email;
                $system_data['system_mobile']=$data->system_mobile;
                $system_data['system_address']=$data->system_address;
                $system_data['system_details']=$data->system_details;
                $system_data['password_validity_days']=$data->password_validity_days;
                $system_data['password_min_length']=$data->password_min_length;
                $system_data['default_password_type']=$data->default_password_type;
                $system_data['userid_min_length']=$data->userid_min_length;
                $system_data['password_max_length']=$data->password_max_length;
                $system_data['userid_max_length']=$data->userid_max_length;
                $system_data['session_time']=$data->session_time;
                $system_data['session_check_time']=$data->session_check_time;

            }
            else{

                $system_data['system_name']='Admin';
                $system_data['system_title']='Admin Dashboard';
                $system_data['system_icon']='icon.png';
                $system_data['system_logo']='logo.png';
                $system_data['system_version']='1.0.0';
                $system_data['system_copy_right']='Copyright © 2024-'.date('Y').' Admin All rights reserved.';
                $system_data['system_bg_image']='login_bg.jpg';
                $system_data['system_slogan']='Admin';
                $system_data['system_email']='minulhasanrokan@gmail.com';
                $system_data['system_mobile']='+8801627197089';
                $system_data['system_address']='215/A, Outer Circular Road, Baro Moghbazar, Dhaka 1217, Bangladesh';
                $system_data['system_details']='Experienced Programmer and Solutions Architect specializing in PHP (Laravel & CodeIgniter) with a strong foundation in database management (Oracle, MySQL, SQL Server). I bring over 3 years of expertise in developing ERP and banking solutions that streamline operations, enhance data security, and drive business efficiency. My passion lies in crafting custom software solutions that empower businesses to excel in the ever-evolving world of finance and enterprise resource planning. I take pride in my ability to create scalable, high-performance systems that meet the unique demands of the banking sector and ERP platforms. Let\'s connect to discuss potential collaborations, industry insights, or explore ways to leverage technology to achieve your business goals. I\'m always eager to share knowledge and contribute to innovative projects that shape the future of finance and business operations.';

                $system_data['password_validity_days']=30;
                $system_data['password_min_length']=6;
                $system_data['default_password_type']=1;
                $system_data['userid_min_length']=8;
                $system_data['password_max_length']=8;
                $system_data['userid_max_length']=8;
                $system_data['session_time']=60;
                $system_data['session_check_time']=3;
            }

            Common::put_cahce_value('system_settings',$system_data);

            return $system_data;
        }
    }

    public static function update_user_log_time($log_out_status=0,$force_status=0){

        $updateData = [
            'last_active_time' => now(),
            'log_out_time' => now(),
            'log_out_status' => $log_out_status,
            'force_status' => $force_status,
        ];

        try {

            $session_data = CommonController::get_session_data();

            $user_log_id = $session_data['user_log_id'];

            DB::table('user_log_histories')->where('id', $user_log_id) ->update($updateData);
            
            return 1;

        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public static function add_user_activity_history($table_name=null,$table_id=null,$activity=null,$search_data=null,$actual_status=null,$process_status=null){

        $session_data = CommonController::get_session_data();

        $action_data = Common::get_single_route_details();

        $group_id = isset($action_data->group_id)?$action_data->group_id:'';
        $cat_id = isset($action_data->cat_id)?$action_data->cat_id:'';
        $right_id = isset($action_data->id)?$action_data->id:'';

        $ip_address = request()->ip();
        $mac_address = '';

        $data_arr = array(
            'group_id' => $group_id,
            'category_id' => $cat_id,
            'right_id' => $right_id,
            'table_name' =>$table_name,
            'table_id' =>$table_id,
            'activity' =>$activity,
            'process_status' =>$process_status,
            'actual_status' =>$actual_status,
            'ip_address' =>$ip_address,
            'mac_address' =>$mac_address,
            'search_data' =>$search_data,
            'add_by' => $session_data['id'],
            'created_at' =>now()
        );

        try {

            DB::table('activity_histories')->insert($data_arr);
            
            return 1;
            
        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public static function get_single_route_details(){

        $route_name = request()->route()->getName();

        $session_data = CommonController::get_session_data();

        //Common::delete_cahce_data('menu_right:*');

        if($session_data['super_admin_status']==1){

            $right_data = Common::get_cache_value_by_key('menu_right:admin_single_right'.$route_name);

            if ($right_data['cache_status']==1){
            
                return $right_data['cache_data'];
            }
            else{

                $right_data = DB::table('right_details as a')
                    ->join('right_categories as b', 'b.id', '=', 'a.cat_id')
                    ->join('right_groups as d', 'd.id', '=', 'b.group_id')
                    ->select('a.*','d.id as group_id')
                    ->where('a.status',1)
                    ->where('a.r_route_name',$route_name)
                    ->where('a.delete_status',0)
                    ->first();

                Common::put_cahce_value('menu_right:admin_single_right'.$route_name,$right_data);

                return $right_data;
            }
        }
        else{

            $right_data = Common::get_cache_value_by_key('menu_right:user_single_right'.$session_data['id'].$route_name);

            if ($right_data['cache_status']==1){
            
                return $right_data['cache_data'];
            }
            else{

                $right_data = DB::table('right_details as a')
                    ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                    ->join('right_categories as b', 'b.id', '=', 'a.cat_id')
                    ->join('right_groups as d', 'd.id', '=', 'b.group_id')
                    ->select('a.*','d.id as group_id')
                    ->where('a.status',1)
                    ->where('c.user_id',$session_data['id'])
                    ->where('a.r_route_name',$route_name)
                    ->where('a.delete_status',0)
                    ->orderBy('a.r_short_order', 'ASC')
                    ->first();

                Common::put_cahce_value('menu_right:user_single_right'.$session_data['id'].$route_name,$right_data);

                return $right_data;
            }
        }
    }

    public static function get_user_log_histories_data(){

        $session_data = CommonController::get_session_data();

        $log_data = DB::table('user_log_histories')
                ->select('id')
                ->where('delete_status',0)
                ->where('log_out_status',0)
                ->where('force_status',0)
                ->where('id',$session_data['user_log_id']);

        return $total_log_data = $log_data->count();
    }

    public static function get_duplicate_value($field_name,$table_name, $value, $data_id=0){

        $field_name = trim($field_name);
        $table_name = trim($table_name);
        $value = urldecode(trim($value));
        $data_id = trim($data_id);

        $right_data = DB::table($table_name)
            ->where('delete_status',0)
            ->where($field_name,$value);

        if($data_id!=0 && $data_id!='' && $data_id>0){
                
            $right_data->where('id','!=',$data_id);
        }

        $data = $right_data->count();

        return $data;
    }

    public static function get_combain_duplicate_value($table_name,$field_values,$data_id){

        $table_name = trim($table_name);
        $data_id = trim($data_id);
        $field_values  = $field_values;

        $right_data = DB::table($table_name)
            ->where('delete_status',0)
            ->where($field_values);

        if($data_id!=0 && $data_id!='' && $data_id>0){
                
            $right_data->where('id','!=',$data_id);
        }

        $data = $right_data->count();

        return $data;
    }

    public static function get_page_menu(){

        $route_name = request()->route()->getName();

        $session_data = CommonController::get_session_data();

        //Common::delete_cahce_data('menu_right:*');

        if($session_data['super_admin_status']==1){

            $right_data = Common::get_cache_value_by_key('menu_right:admin_right'.$route_name);

            if ($right_data['cache_status']==1){
            
                return $right_data['cache_data'];
            }
            else{

                $right_data = DB::table('right_details as a')
                    ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                    ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon', 'a.popup_status', 'a.width')
                    ->where('a.status',1)
                    ->where('b.r_route_name',$route_name)
                    ->where('a.delete_status',0)
                    ->orderBy('a.r_short_order', 'ASC')
                    ->get()->toArray();

                $menu_data = '';

                foreach($right_data as $data){

                    $menu_data .='<button onclick="get_new_page(\''.route($data->r_route_name).'\',\''.$data->r_title.'\',\'\',\'\',\''.$data->popup_status.'\',\''.$data->width.'\');" type="button" class="btn btn-primary" style="margin-right:10px;"><i class="fa '.$data->r_icon.'"></i>&nbsp;'.$data->r_name.'</button>';
                }

                Common::put_cahce_value('menu_right:admin_right'.$route_name,$menu_data);

                return $menu_data;
            }
        }
        else{

            $right_data = Common::get_cache_value_by_key('menu_right:user_right'.$session_data['id'].$route_name);

            if ($right_data['cache_status']==1){
            
                return $right_data['cache_data'];
            }
            else{
                
                $right_data = DB::table('right_details as a')
                    ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                    ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                    ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon', 'a.popup_status', 'a.width')
                    ->where('a.status',1)
                    ->where('c.user_id',$session_data['id'])
                    ->where('b.r_route_name',$route_name)
                    ->where('a.delete_status',0)
                    ->orderBy('a.r_short_order', 'ASC')
                    ->get()->toArray();

                $menu_data = '';

                foreach($right_data as $data){

                    $menu_data .='<button onclick="get_new_page(\''.route($data->r_route_name).'\',\''.$data->r_title.'\',\'\',\'\',\''.$data->popup_status.'\',\''.$data->width.'\');" type="button" class="btn btn-primary" style="margin-right:10px;"><i class="fa '.$data->r_icon.'"></i>&nbsp;'.$data->r_name.'</button>';
                }
                
                Common::put_cahce_value('menu_right:user_right'.$session_data['id'].$route_name,$menu_data);

                return $menu_data;
            }
        }
    }

    public static function get_data_status_data(){

        return [
            '0' =>'Deactive',
            '1' => 'Active'
        ];
    }

    public static function get_actual_status_data(){

        //Common::delete_cahce_data('actual_status');

        $data = Common::get_cache_value_by_key('actual_status');

        if ($data['cache_status']==1){
        
            return $data['cache_data'];
        }
        else{
            
            $data = DB::table('actual_process_statuses as a')
                ->where('a.delete_status',0)
                ->orderBy('a.status_name', 'ASC')
                ->get()->toArray();

            $data_arr = array();

            foreach($data as $value){

                $data_arr[$value->id] = $value->status_name;
            }

            Common::put_cahce_value('actual_status',$data_arr);

            return $data_arr;
        }
    }

    public static function get_process_status_data(){

        //Common::delete_cahce_data('process_status');

        $data = Common::get_cache_value_by_key('process_status');

        if ($data['cache_status']==1){
        
            return $data['cache_data'];
        }
        else{
            
            $data = DB::table('process_statuses as a')
                ->where('a.delete_status',0)
                ->orderBy('a.status_name', 'ASC')
                ->get()->toArray();

            $data_arr = array();

            foreach($data as $value){

                $data_arr[$value->id] = $value->status_name;
            }

            Common::put_cahce_value('process_status',$data_arr);

            return $data_arr;
        }
    }

    public static function return_message_history_data($id=null,$table_name=null){

        $data = DB::table('return_messages as a')
            ->select('a.*','ur.name as user_name')
            ->leftJoin('users as ur', 'a.add_by', '=', 'ur.id')
            ->where('a.delete_status',0)
            ->where('a.table_name',$table_name)
            ->where('a.table_id',$id)
            ->orderBy('a.id', 'desc')
            ->get();

        return $data;
    }

    public static function activity_history_data($id=null,$table_name=null){

        $data = DB::table('activity_histories as a')
            ->select('a.*','ur.name as user_name')
            ->leftJoin('users as ur', 'a.add_by', '=', 'ur.id')
            ->where('a.delete_status',0)
            ->where('a.table_name',$table_name)
            ->where('a.table_id',$id)
            ->orderBy('a.id', 'desc')
            ->get();

        return $data;
    }

    public static function get_page_menu_grid($action_name){

        $route_name = request()->route()->getName();

        $session_data = CommonController::get_session_data();

        //Common::delete_cahce_data('menu_right:*');

        if($session_data['super_admin_status']==1){

            $right_data = Common::get_cache_value_by_key('menu_right:get_page_menu_grid'.$route_name);

            if ($right_data['cache_status']==1){
        
                return $right_data['cache_data'];
            }
            else{

                $action_name_arr = explode("****",$action_name);

                $right_data = DB::table('right_details as a')
                    ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                    ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon', 'a.popup_status', 'a.width')
                    ->where('a.status',1)
                    ->where('b.r_route_name',$route_name)
                    ->whereNotIn('a.r_action_name',$action_name_arr)
                    ->where('a.delete_status',0)
                    ->orderBy('a.r_short_order', 'ASC')
                    ->get();

                $user_right = array();

                $sl = 0;

                foreach($right_data as $right){

                    $user_right[$sl]['r_id'] = $right->r_id;
                    $user_right[$sl]['cat_id'] = $right->cat_id;
                    $user_right[$sl]['r_name'] = $right->r_name;
                    $user_right[$sl]['r_title'] = $right->r_title;
                    $user_right[$sl]['r_action_name'] = $right->r_action_name;
                    $user_right[$sl]['r_route_name'] = $right->r_route_name;
                    $user_right[$sl]['r_details'] = $right->r_details;
                    $user_right[$sl]['r_short_order'] = $right->r_short_order;
                    $user_right[$sl]['r_icon'] = $right->r_icon;
                    $user_right[$sl]['popup_status'] = $right->popup_status;
                    $user_right[$sl]['width'] = $right->width;

                    $sl++;
                }

                Common::put_cahce_value('menu_right:get_page_menu_grid'.$route_name,$user_right);

                return $user_right;
            }
        }
        else{

            $right_data = Common::get_cache_value_by_key('menu_right:get_page_menu_grid'.$session_data['id'].$route_name);

            if ($right_data['cache_status']==1){
        
                return $right_data['cache_data'];
            }
            else{

                $action_name_arr = explode("****",$action_name);

                $right_data = DB::table('right_details as a')
                    ->join('right_details as b', 'b.cat_id', '=', 'a.cat_id')
                    ->join('user_rights as c', 'c.r_id', '=', 'a.id')
                    ->select('a.id  as r_id', 'a.cat_id', 'a.r_name', 'a.r_title', 'a.r_action_name', 'a.r_route_name', 'a.r_details', 'a.r_short_order', 'a.r_icon', 'a.popup_status', 'a.width')
                    ->where('a.status',1)
                    ->where('c.user_id',$session_data['id'])
                    ->where('b.r_route_name',$route_name)
                    ->whereNotIn('a.r_action_name',$action_name_arr)
                    ->where('a.delete_status',0)
                    ->orderBy('a.r_short_order', 'ASC')
                    ->get();

                $user_right = array();

                $sl = 0;

                foreach($right_data as $right){

                    $user_right[$sl]['r_id'] = $right->r_id;
                    $user_right[$sl]['cat_id'] = $right->cat_id;
                    $user_right[$sl]['r_name'] = $right->r_name;
                    $user_right[$sl]['r_title'] = $right->r_title;
                    $user_right[$sl]['r_action_name'] = $right->r_action_name;
                    $user_right[$sl]['r_route_name'] = $right->r_route_name;
                    $user_right[$sl]['r_details'] = $right->r_details;
                    $user_right[$sl]['r_short_order'] = $right->r_short_order;
                    $user_right[$sl]['r_icon'] = $right->r_icon;
                    $user_right[$sl]['popup_status'] = $right->popup_status;
                    $user_right[$sl]['width'] = $right->width;

                    $sl++;
                }

                Common::put_cahce_value('menu_right:get_page_menu_grid'.$session_data['id'].$route_name,$user_right);

                return $user_right;
            }
        }
    }

    public static function get_all_right(){

        //Common::delete_cahce_data('menu_right:*');

        $right_data = Common::get_cache_value_by_key('menu_right:all_right');

        if ($right_data['cache_status']==1){
    
            return $right_data['cache_data'];
        }
        else{

            $right_data = DB::table('right_details')
                ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
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

            $group_arr = array();
            $cat_arr = array();
            $right_arr = array();

            foreach($right_data as $data){

                $group_arr[$data->g_id]['g_id'] = $data->g_id;
                $group_arr[$data->g_id]['g_name'] = $data->g_name;
                $group_arr[$data->g_id]['g_action_name'] = $data->g_action_name;
                $group_arr[$data->g_id]['g_details'] = $data->g_details;
                $group_arr[$data->g_id]['g_title'] = $data->g_title;
                $group_arr[$data->g_id]['g_short_order'] = $data->g_short_order;
                $group_arr[$data->g_id]['g_icon'] = $data->g_icon;

                $cat_arr[$data->g_id][$data->c_id]['c_id'] = $data->c_id;
                $cat_arr[$data->g_id][$data->c_id]['c_name'] = $data->c_name;
                $cat_arr[$data->g_id][$data->c_id]['c_title'] = $data->c_title;
                $cat_arr[$data->g_id][$data->c_id]['c_action_name'] = $data->c_action_name;
                $cat_arr[$data->g_id][$data->c_id]['c_details'] = $data->c_details;
                $cat_arr[$data->g_id][$data->c_id]['c_short_order'] = $data->c_short_order;
                $cat_arr[$data->g_id][$data->c_id]['c_icon'] = $data->c_icon;

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_name'] = $data->r_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_title'] = $data->r_title;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_action_name'] = $data->r_action_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_route_name'] = $data->r_route_name;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_details'] = $data->r_details;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_short_order'] = $data->r_short_order;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_icon'] = $data->r_icon;
            }

            $right['group_arr'] = $group_arr;
            $right['cat_arr'] = $cat_arr;
            $right['right_arr'] = $right_arr;

            Common::put_cahce_value('menu_right:all_right',$right);

            return $right;
        }
    }

    public static function get_group_right($group_id){

        //Common::delete_cahce_data('menu_right:*');

        $right_data = Common::get_cache_value_by_key('menu_right:group_right'.$group_id);

        if ($right_data['cache_status']==1){
    
            return $right_data['cache_data'];
        }
        else{
            
            $right_data = DB::table('user_group_rights')
                ->select('group_id','g_id','c_id','r_id','add_by','edit_by')
                ->where('status',1)
                ->where('delete_status',0)
                ->where('group_id',$group_id)
                ->get()
                ->toArray();

            $right_arr = array();

            foreach($right_data as $data){

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['add_by'] = $data->add_by;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['edit_by'] = $data->edit_by;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['group_id'] = $data->group_id;
            }

            Common::put_cahce_value('menu_right:group_right'.$group_id,$right_arr);

            return $right_arr;
        }
    }

    public static function get_user_right($user_id){

        //Common::delete_cahce_data('menu_right:*');

        $right_data = Common::get_cache_value_by_key('menu_right:user_right'.$user_id);

        if ($right_data['cache_status']==1){
    
            return $right_data['cache_data'];
        }
        else{
            
            $right_data = DB::table('user_rights')
                ->select('user_id','g_id','c_id','r_id','add_by','edit_by')
                ->where('status',1)
                ->where('delete_status',0)
                ->where('user_id',$user_id)
                ->get()
                ->toArray();

            $right_arr = array();

            foreach($right_data as $data){

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['add_by'] = $data->add_by;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['edit_by'] = $data->edit_by;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['user_id'] = $data->user_id;
            }

            Common::put_cahce_value('menu_right:user_right'.$user_id,$right_arr);

            return $right_arr;
        }
    }

    public static function insert_return_message($table_name=null,$table_id=null,$actual_status=null,$process_status=null,$message=null){

        $session_data = CommonController::get_session_data();

        $action_data = Common::get_single_route_details();

        $group_id = isset($action_data->group_id)?$action_data->group_id:'';
        $cat_id = isset($action_data->cat_id)?$action_data->cat_id:'';
        $right_id = isset($action_data->id)?$action_data->id:'';

        $data_arr = array(
            'group_id' => $group_id,
            'category_id' => $cat_id,
            'right_id' => $right_id,
            'table_name' =>$table_name,
            'table_id' =>$table_id,
            'message' =>$message,
            'process_status' =>$process_status,
            'actual_status' =>$actual_status,
            'add_by' => $session_data['id'],
            'created_at' =>now()
        );

        try {

            DB::table('return_messages')->insert($data_arr);
            
            return 1;
            
        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public static function dashboard_right(){

        $session_data = CommonController::get_session_data();

        $admin_status = $session_data['super_admin_status'];

        //Common::delete_cahce_data('menu_right:*');

        if($admin_status==1)
        {
            $right_data = Common::get_cache_value_by_key('menu_right:dashboard_right');

            if ($right_data['cache_status']==1){
    
                return $right_data['cache_data'];
            }
            else{

                $right_data = DB::table('right_details')
                    ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                    ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                    ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
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

                $right_group_icon_arr = array();
                $dashboard_right_arr = array();

                foreach($right_data as $data){

                    $dashboard_right_arr['r_route_name'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_route_name;
                    $dashboard_right_arr['r_title'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_title;
                    $dashboard_right_arr['r_icon'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_icon;
                    $dashboard_right_arr['r_action_name'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_action_name;

                    $right_group_icon_arr[$data->g_action_name][$data->c_action_name]['c_icon'] = $data->c_icon;
                }

                $dashboard_right['dashboard_right_arr'] = $dashboard_right_arr;
                $dashboard_right['right_group_icon_arr'] = $right_group_icon_arr;

                Common::put_cahce_value('menu_right:dashboard_right',$dashboard_right);

                return $dashboard_right;
            }
        }
        else{

            $right_data = Common::get_cache_value_by_key('menu_right:dashboard_right'.$session_data['id']);

            if ($right_data['cache_status']==1){
    
                return $right_data['cache_data'];
            }
            else{
                
                $right_data = DB::table('right_details')
                    ->join('user_rights', 'right_details.id', '=', 'user_rights.r_id')
                    ->join('right_categories', 'right_categories.id', '=', 'right_details.cat_id')
                    ->join('right_groups', 'right_groups.id', '=', 'right_categories.group_id')
                    ->select('right_groups.id as g_id', 'right_groups.name as g_name', 'right_groups.action_name as g_action_name', 'right_groups.details as g_details', 'right_groups.title as g_title', 'right_groups.short_order as g_short_order', 'right_groups.icon as g_icon', 'right_categories.id as c_id', 'right_categories.group_id as group_id', 'right_categories.c_name', 'right_categories.c_title', 'right_categories.c_action_name', 'right_categories.c_details', 'right_categories.short_order as c_short_order', 'right_categories.c_icon as c_icon', 'right_details.id  as r_id', 'right_details.cat_id', 'right_details.r_name', 'right_details.r_title', 'right_details.r_action_name', 'right_details.r_route_name', 'right_details.r_details', 'right_details.r_short_order', 'right_details.r_icon')
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

                $right_group_icon_arr = array();
                $dashboard_right_arr = array();

                foreach($right_data as $data){

                    $dashboard_right_arr['r_route_name'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_route_name;
                    $dashboard_right_arr['r_title'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_title;
                    $dashboard_right_arr['r_icon'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_icon;
                    $dashboard_right_arr['r_action_name'][$data->g_action_name][$data->c_action_name][$data->r_action_name] = $data->r_action_name;

                    $right_group_icon_arr[$data->g_action_name][$data->c_action_name]['c_icon'] = $data->c_icon;
                }

                $dashboard_right['dashboard_right_arr'] = $dashboard_right_arr;
                $dashboard_right['right_group_icon_arr'] = $right_group_icon_arr;

                Common::put_cahce_value('menu_right:dashboard_right'.$session_data['id'],$dashboard_right);

                return $dashboard_right;
            }
        }
    }

    public static function get_single_data_by_value($table_name,$field_name,$value){

        $dropdown_data = Common::get_cache_value_by_key('dropdown:single_data'.$table_name.$field_name.$value);

        if ($dropdown_data['cache_status']==1){

            return $dropdown_data['cache_data'];
        }
        else{

            $data = DB::table($table_name)
                ->select('*')
                ->where($field_name,$value)
                ->where('delete_status',0)
                ->where('status',1)
                ->first();

            Common::put_cahce_value('dropdown:single_data'.$table_name.$field_name.$value,$data);

            return $data;
        }
    }

    public static function get_dropdown($table_name,$field_name,$value,$other_field,$other_value,$not_equal_field,$not_equal_value){

        $table_name = trim($table_name);
        $field_name = trim($field_name);

        //Common::delete_cahce_data('dropdown:*');

        if($value!='' && $value!=0){

            $dropdown_data = Common::get_cache_value_by_key('dropdown:'.$table_name.$field_name.$value.$other_field.$other_value.$not_equal_field.$not_equal_value);

            if ($dropdown_data['cache_status']==1){
    
                return $dropdown_data['cache_data'];
            }
            else{

                $other_field_arr = array();
                $other_value_arr = array();
                $where_other_arr = array();

                if($other_field!='' && $other_field!=0)
                {
                    $other_field_arr = explode("****",$other_field);
                    $other_value_arr = explode("****",$other_value);
                }

                if(count($other_field_arr)==1){

                    $other_value_arr = explode(",",$other_value);
                }
                else{

                    foreach($other_field_arr as $key=>$other_data){

                        $where_other_arr[$other_data] = $other_value_arr[$key];
                    }
                }

                if($field_name=='' || $field_name==null || $field_name==0){

                    $select = "*";
                }
                else{

                    $field_name_arr = explode(",",$field_name);

                    $sl =0;

                    foreach($field_name_arr as $data){

                        $select[$sl] = $data;

                        $sl++;
                    }
                }

                $where_arr = array(
                    'delete_status' =>0,
                    'status' => 1
                );

                $value_arr = explode(",",$value);

                $data = DB::table($table_name)
                    ->select($select)
                    ->where($where_arr);

                if(count($other_field_arr)==1){

                    if (strpos(strtoupper($other_field), 'ASSIGN') !== false) {

                        $data->where(function ($query) use ($other_field,$other_value_arr) {

                            $conditions = implode('', array_map(function ($value) use ($other_field) {

                                if($value!=''){

                                    return "OR FIND_IN_SET($value, $other_field) ";
                                }
                            }, $other_value_arr));

                            if($conditions!=''){

                                $conditions = substr($conditions, 2);

                                $query->whereRaw($conditions);
                            }
                        });
                    }
                    else{

                        $data->whereIn($other_field, $other_value_arr);
                    }
                }
                else{

                    $data->where($where_other_arr);
                }

                if($not_equal_field!=0 && $not_equal_field!=''){

                    $data->where($not_equal_field, '!=', $not_equal_value);
                }

                $data->orWhereIn('id',$value_arr);

                $result = $data->get()
                    ->toArray();

                Common::put_cahce_value('dropdown:'.$table_name.$field_name.$value.$other_field.$other_value.$not_equal_field.$not_equal_value,$result);

                return $result;
            }
        }
        else{

            $dropdown_data = Common::get_cache_value_by_key('dropdown:'.$table_name.$field_name.$value.$other_field.$other_value.$not_equal_field.$not_equal_value);

            if ($dropdown_data['cache_status']==1){
    
                return $dropdown_data['cache_data'];
            }
            else{

                $other_field_arr = array();
                $other_value_arr = array();
                $where_other_arr = array();

                if($other_field!='' && $other_field!=0)
                {
                    $other_field_arr = explode("****",$other_field);
                    $other_value_arr = explode("****",$other_value);
                }

                if(count($other_field_arr)==1){

                    $other_value_arr = explode(",",$other_value);
                }
                else{

                    foreach($other_field_arr as $key=>$other_data){

                        $where_other_arr[$other_data] = $other_value_arr[$key];
                    }
                }

                if($field_name=='' || $field_name==null || $field_name==0){

                    $select = "*";
                }
                else{

                    $field_name_arr = explode(",",$field_name);

                    $sl =0;

                    foreach($field_name_arr as $data){

                        $select[$sl] = $data;

                        $sl++;
                    }
                }

                $data = DB::table($table_name)
                    ->select($select)
                    ->where('delete_status',0)
                    ->where('status',1);
                
                if(count($other_field_arr)==1){

                    if (strpos(strtoupper($other_field), 'ASSIGN') !== false) {

                        $data->where(function ($query) use ($other_field,$other_value_arr) {

                            $conditions = implode('', array_map(function ($value) use ($other_field) {

                                if($value!=''){

                                    return "OR FIND_IN_SET($value, $other_field) ";
                                }
                            }, $other_value_arr));

                            if($conditions!=''){

                                $conditions = substr($conditions, 2);

                                $query->whereRaw($conditions);
                            }
                        });
                    }
                    else{

                        $data->whereIn($other_field, $other_value_arr);
                    }
                }
                else{

                    $data->where($where_other_arr);
                }

                if($not_equal_field!=0 && $not_equal_field!=''){

                    $data->where($not_equal_field, '!=', $not_equal_value);
                }

                $result = $data->get()
                    ->toArray();

                Common::put_cahce_value('dropdown:'.$table_name.$field_name.$value.$other_field.$other_value.$not_equal_field.$not_equal_value,$result);

                return $result;

            }
        }
    }

    public static function insert_password_history($user_id,$password){

        try {

            $data_arr['user_id'] = $user_id;
            $data_arr['password'] = $password;

            return DB::table('password_histories')->insertGetId($data_arr);
            
        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public static function check_previous_four_password($user_id, $new_assword) {

        $passwords = DB::table('password_histories')
                        ->select('password')
                        ->where('user_id', $user_id)
                        ->orderBy('id', 'desc')
                        ->limit(4)
                        ->get();
   
        foreach ($passwords as $data){

            if (Hash::check($new_assword, $data->password)) {
                return 0;
            }
        }

        return 1;
    }

    public static function insert_mail_history($mail_address=null,$cc_mail_address=null,$table_name=null,$table_id=0,$subject=null,$body=null,$sent_status=0,$try_status=0){

        try {

            $data_arr['mail_address'] = $mail_address;
            $data_arr['table_name'] = $table_name;
            $data_arr['table_id'] = $table_id;
            $data_arr['subject'] = $subject;
            $data_arr['body'] = $body;
            $data_arr['sent_status'] = $sent_status;
            $data_arr['try_status'] = $try_status;
            $data_arr['cc_mail_address'] = $cc_mail_address;

            $status =  DB::table('mail_histories')->insert($data_arr);

            return $status;
            
        } catch (\Exception $e) {
            
            return 0;
        }
    }

    public static function db_transaction_on(){

        DB::beginTransaction();
    }

    public static function db_transaction_commit(){

        DB::commit();
    }

    public static function db_transaction_rollBack(){

        DB::rollBack();
    }

    public static function get_assign_data($value,$table_name,$column_name,$select,$style){

        if($value=='' || $value==null || $value==','){

            return '';
        }

        $value_arr = explode(",",$value);

        $data = DB::table($table_name)
            ->select($select)
            ->WhereIn($column_name,$value_arr)
            ->get();

        $return_data = '';

        if($style=='comma'){

            foreach($data as $row){

                if($return_data!=''){

                    $return_data .=', ';
                }

                $return_data .=$row->$select;
            }
        }

        return $return_data;
    }
}