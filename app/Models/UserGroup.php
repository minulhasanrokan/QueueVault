<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class UserGroup extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $insert_data = [
            'group_name' => $request->group_name,
            'group_code' => $request->group_code,
            'group_title' => $request->group_title,
            'group_details' => $request->group_details,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        if ($request->hasFile('group_logo')) {

            $file = $request->file('group_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            $file->move('uploads/user_group/',$fileName);

            $insert_data['group_logo'] = $fileName;
        }

        if ($request->hasFile('group_icon')) {

            $file = $request->file('group_icon');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_icon_'.time().'.'.$extension;

            $file->move('uploads/user_group/',$fileName);

            $insert_data['group_icon'] = $fileName;
        }

        Common::db_transaction_on();

        $data = UserGroup::create($insert_data);

        if($data==true){

            $insert_history = UserGroup::insert_history($data->id);

            $status = Common::add_user_activity_history('user_groups',$data->id,'Add user Group Information ('.$request->group_name.')','',1,0);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $data->id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function insert_history($insert_id=null){

        $insert_status = 0;

        $sql = "INSERT INTO user_group_histories (main_id, group_name, group_code, group_title, group_details, group_logo, group_icon, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, group_name, group_code, group_title, group_details, group_logo, group_icon, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM user_groups where Id=:insert_id";

        try {

            DB::statement($sql, ['insert_id' => $insert_id]);

            $insert_status= DB::getPdo()->lastInsertId();
        }
        catch (\Exception $e) {

            $insert_status = 0;
        }
        
        return $insert_status;
    }

    public static function update_history($insert_id=null,$group_logo,$group_icon,$request){

        $insert_status = 0;

        $sql = "INSERT INTO user_group_histories (main_id, group_name, group_code, group_title, group_details, group_logo, group_icon, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id,'$request->group_name', '$request->group_code', '$request->group_title', '$request->group_details', '$group_logo', '$group_icon', delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM user_groups where Id=:insert_id";

        try {

            DB::statement($sql, ['insert_id' => $insert_id]);

            $insert_status = 1;
        }
        catch (\Exception $e) {

            $insert_status = 0;
        }

        return $insert_status;
    }

    public static function grid_data($request){

        $draw = $request->draw;
        $row = $request->start;
        $row_per_page  = $request->length;

        $column_index  = $request->order[0]['column'];
        $column_name = $request->columns[$column_index]['data'];
        $column_ort_order = $request->order[0]['dir'];
        $search_value  = trim($request->search['value']);

        $total_records = 0;
        $data = array();
        $response = array();

        if($search_value!=''){

            $total_records = UserGroup::where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('group_name','like',"%".$search_value."%")
                        ->orWhere('group_code','like',"%".$search_value."%")
                        ->orWhere('message','like',"%".$search_value."%");
                })
                ->count();

            $data = UserGroup::select('id','group_name', 'group_code', 'message','process_status', 'actual_status', 'status', 'edit_status')
                ->where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('group_name','like',"%".$search_value."%")
                        ->orWhere('group_code','like',"%".$search_value."%")
                        ->orWhere('message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = UserGroup::where('delete_status', 0)->count();

            $data = UserGroup::select('id','group_name', 'group_code', 'message','process_status', 'actual_status', 'status', 'edit_status')
                ->where('delete_status',0)
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }

        $response['draw'] = $draw;
        $response['iTotalRecords'] = $total_records;
        $response['iTotalDisplayRecords'] = $total_records;
        $response['aaData'] = $data;
        $response['csrf_token'] = csrf_token();

        return $response;
    }

    public static function get_single_data_by_id($id=null){

        $data = UserGroup::where('user_groups.delete_status',0)
            ->select('user_groups.*', 'maker.name as maker_by')
            ->leftJoin('users as maker', 'maker.id', '=', 'user_groups.sent_checker_by')
            ->where('user_groups.id',$id)
            ->first();

        return $data;
    }

    public static function get_single_history_data_by_id($id=null){

        $data = DB::table('user_group_histories')
            ->where('delete_status',0)
            ->select('*')
            ->where('main_id',$id)
            ->where('process_status',0)
            ->orderBy('id','desc')
            ->first();

        return $data;
    }

    public static function user_group_history($id){

        $data = DB::table('user_group_histories')
            ->where('delete_status',0)
            ->select('*')
            ->where('main_id',$id)
            ->where('process_status',3)
            ->whereIn('actual_status',[1,2,3])
            ->orderBy('id','desc')
            ->get();

        return $data;
    }

    public static function get_right_data_for_approve($history_id){

        $data = DB::table('user_group_right_histories')
            ->where('delete_status',0)
            ->select('*')
            ->where('history_id',$history_id)
            ->get();

        return $data;
    }

    public static function get_single_right_chnaged_history_data_by_id($id=null){

        $right_data = DB::table('user_group_right_histories')
            ->select('group_id', 'g_id', 'c_id', 'r_id', 'add_by', 'edit_by')
            ->where('status', 1)
            ->where('delete_status', 0)
            ->where('group_id', $id)
            ->where('history_id', function($query) use ($id) {
                $query->select('id')
                      ->from('user_group_histories')
                      ->where('delete_status', 0)
                      ->where('main_id', $id)
                      ->where('actual_status', 8)
                      ->where('process_status', 0)
                      ->orderBy('id', 'desc')
                      ->first();
            })
            ->get()
            ->toArray();

        $right_arr = array();

        if(!empty($right_data)){

            foreach($right_data as $data){

                $right_arr[$data->g_id][$data->c_id][$data->r_id]['r_id'] = $data->r_id;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['add_by'] = $data->add_by;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['edit_by'] = $data->edit_by;
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['group_id'] = $data->group_id;
            }
        }

        return $right_arr;
    }

    public static function update_data($request){

        $session_data = CommonController::get_session_data();

        $update_data = array();

        $group_logo = $request->hidden_group_logo;
        $group_icon = $request->hidden_group_icon;

        if ($request->hasFile('group_logo')) {

            $file = $request->file('group_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            $file->move('uploads/user_group/',$fileName);

            if($group_logo!='')
            {
                $deletePhoto = "uploads/user_group/".$group_logo;
                
                if(file_exists($deletePhoto)){

                    //unlink($deletePhoto);
                }
            }

            $group_logo = $fileName;
        }

        if ($request->hasFile('group_icon')) {

            $file = $request->file('group_icon');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->group_name);

            $fileName = $title_name.'_icon_'.time().'.'.$extension;

            $file->move('uploads/user_group/',$fileName);
            
            if($group_icon!='')
            {
                $deletePhoto = "uploads/user_group/".$group_icon;
                
                if(file_exists($deletePhoto)){

                    //unlink($deletePhoto);
                }
            }

            $group_icon = $fileName;
        }

        $actual_status = 0;
        $des = '';

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){
            
            $update_data = [
                'group_name' => $request->group_name,
                'group_code' => $request->group_code,
                'group_title' => $request->group_title,
                'group_details' => $request->group_details,
                'group_logo' => $group_logo,
                'group_icon' => $group_icon,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2,
            ];

            $actual_status = 2;
            $des = 'Add-Edit user Group Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit user Group Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = UserGroup::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = UserGroup::insert_history($request->update_id);
            }
            else{

                $insert_history = UserGroup::update_history($request->update_id,$group_logo,$group_icon,$request);
            }

            $status = Common::add_user_activity_history('user_groups',$request->update_id,$des,'',$actual_status,0);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $request->update_id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function sent_to_checker($request, $id){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'sent_checker_by' => $session_data['id'],
            'sent_checker_at' => now(),
            'process_status' =>1,
        ];

        Common::db_transaction_on();

        $data = UserGroup::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = UserGroup::insert_history($id);

            $status = Common::add_user_activity_history('user_groups',$id,'Sent To Checker User Group Information','',$request->actual_status,1);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function verify($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = array();

        $process_status = 0;
        $des = '';
        $message_status = 1;

        Common::db_transaction_on();

        if ($request->verify_type==1) {

            if($request->actual_status==1 || $request->actual_status==2 || $request->actual_status==3){

                $history_data = UserGroup::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['group_name'] = $history_data->group_name;
                    $update_data['group_code'] = $history_data->group_code;
                    $update_data['group_title'] = $history_data->group_title;
                    $update_data['group_details'] = $history_data->group_details;
                    $update_data['group_logo'] = $history_data->group_logo;
                    $update_data['group_icon'] = $history_data->group_icon;

                    if($request->actual_status==1 || $request->actual_status==2){

                        $update_data['status'] = 1;
                    }
                }
            }
            else if($request->actual_status==4){

                $update_data['delete_status'] = 1;
            }
            else if($request->actual_status==8){

                $history_data = UserGroup::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $right_data = UserGroup::get_right_data_for_approve($history_data->id);

                    if(!empty($right_data)){

                        $now = now();
                        $sl = 0;

                        $right_arr = array();

                        foreach($right_data as $righ){

                            $right_arr[$sl]['group_id'] = $righ->group_id;
                            $right_arr[$sl]['g_id'] = $righ->g_id;
                            $right_arr[$sl]['c_id'] = $righ->c_id;
                            $right_arr[$sl]['r_id'] = $righ->r_id;
                            $right_arr[$sl]['add_by'] = $session_data['id'];
                            $right_arr[$sl]['created_at'] = $now;

                            $sl++;
                        }

                        if(!empty($right_arr)){

                            DB::table('user_group_rights')->where('group_id', '=', $id)->delete();

                            DB::table('user_group_rights')->insert($right_arr);

                            Common::delete_cahce_data('menu_right:*');
                        }
                    }
                }
            }
            
            $process_status = 3;
            $des = 'Approve User Group Information';
        }
        else{

            $process_status = 2;
            $des = 'Return User Group Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('user_groups',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = UserGroup::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:user_groups*');

            $insert_history = UserGroup::insert_history($id);

            $status = Common::add_user_activity_history('user_groups',$id,$des,'',$request->actual_status,$process_status);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function delete_user_group($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'actual_status' => 4,
            'process_status' => 0,
            'message' => $request->delete_reason,
            'delete_by' => $session_data['id'],
            'deleted_at' => now()
        ];

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){

            $update_data['delete_status'] = 1;
        }

        Common::db_transaction_on();

        $data = UserGroup::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('user_groups',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = UserGroup::insert_history($id);

            $status = Common::add_user_activity_history('user_groups',$id,'Delete User Group Information','',4,0);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function deactive_user_group($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'actual_status' => 5,
            'status' => 0,
            'process_status' =>0,
            'message' => $request->deactive_reason,
            'dactive_by' => $session_data['id'],
            'dactive_at' =>now(),
        ];

        Common::db_transaction_on();

        $data = UserGroup::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('user_groups',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:user_groups*');

            $insert_history = UserGroup::insert_history($id);

            $status = Common::add_user_activity_history('user_groups',$id,'Deactive User Group Information','',5,0);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function set_right_user_group($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'actual_status' => 8,
            'process_status' =>0,
            'edit_by' => $session_data['id'],
            'updated_at' =>now(),
        ];

        Common::db_transaction_on();

        $data = UserGroup::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = UserGroup::insert_history($id);

            if($insert_history!=0){

                $right_arr = array();
                $sl=0;

                $max_group = $request->g_id_max;

                $now = now();

                for($i=1; $i<=$max_group; $i++){

                    $g_id_name = 'g_id_'.$i;

                    $g_id = $request->$g_id_name;

                    $cat_id = 'c_id_max_'.$i;

                    $max_cat = $request->$cat_id;

                    for($j=1; $j<=$max_cat; $j++){

                        $c_id_name = 'c_id_'.$i."_".$j;

                        $c_id = $request->$c_id_name;

                        $r_id = 'r_id_max_'.$i."_".$j;

                        $max_r_id = $request->$r_id;

                        for($k=1; $k<=$max_r_id; $k++){

                            $r_status_name = 'r_id_checkbox_'.$i."_".$j."_".$k;
                            $r_status = $request->$r_status_name;

                            if($r_status==1){

                                $r_id_name = 'r_id_'.$i."_".$j."_".$k;

                                $r_id = $request->$r_id_name;

                                $right_arr[$sl]['group_id'] = $id;
                                $right_arr[$sl]['g_id'] = $g_id;
                                $right_arr[$sl]['c_id'] = $c_id;
                                $right_arr[$sl]['r_id'] = $r_id;
                                $right_arr[$sl]['add_by'] = $session_data['id'];
                                $right_arr[$sl]['created_at'] = $now;
                                $right_arr[$sl]['history_id'] = $insert_history;

                                $sl++;
                            }
                        }
                    }
                }

                if(!empty($right_arr)){

                    $status = DB::table('user_group_right_histories')->insert($right_arr);

                    if($status==true){

                        $status = Common::add_user_activity_history('user_groups',$id,'Update User Group Right Information','',8,0);

                        if($status==1){

                            Common::db_transaction_commit();

                            return $id;
                        }
                        else{

                            Common::db_transaction_rollBack();

                            return 0;
                        }
                    }
                    else{

                        Common::db_transaction_rollBack();

                        return 0;
                    }
                }
                else{

                    Common::db_transaction_rollBack();

                    return 0;
                }
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function active_user_group($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'actual_status' => 6,
            'status' => 1,
            'process_status' =>0,
            'message' => $request->active_reason,
            'active_by' => $session_data['id'],
            'active_at' =>now(),
        ];

        Common::db_transaction_on();

        $data = UserGroup::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('user_groups',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:user_groups*');

            $insert_history = UserGroup::insert_history($id);

            $status = Common::add_user_activity_history('user_groups',$id,'Active User Group Information','',6,0);

            if($insert_history!=0 && $status==1){

                Common::db_transaction_commit();

                return $id;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }
}