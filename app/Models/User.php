<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\MailController;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', 
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $insert_data = [
            'user_id' => $request->user_id,
            'name' => $request->name,
            'group_id' => $request->group_id,
            'branch_id' => $request->branch_id,
            'department' => $request->department,
            'assign_department' => $request->assign_department,
            'designation' => $request->designation,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'details' => $request->details,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        if ($request->hasFile('user_photo')) {

            $file = $request->file('user_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            $file->move('uploads/user/',$fileName);

            $insert_data['user_photo'] = $fileName;
        }

        Common::db_transaction_on();

        $data = User::create($insert_data);

        if($data==true){

            $insert_history = User::insert_history($data->id);

            $status = Common::add_user_activity_history('users',$data->id,'Add Employee/User Information ('.$request->name.')','',1,0);

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

        $sql = "INSERT INTO user_histories (main_id, name, email, mobile, address, details, department, assign_department, designation, group_id, date_of_birth, sex, blood_group, user_photo, verify_status, delete_status, user_session_time, session_check_time, status, process_status, edit_status, super_admin_status, lock_status, user_type, password, password_change_status, password_change_date, add_by, edit_by, delete_by, verify_by, sent_checker_by, email_verified_at, remember_token, created_at, updated_at, deleted_at, verify_at, sent_checker_at, user_id, branch_id, actual_status, message, active_by, dactive_by, active_at, dactive_at, password_exp_date, block_status) SELECT id, name, email, mobile, address, details, department, assign_department, designation, group_id, date_of_birth, sex, blood_group, user_photo, verify_status, delete_status, user_session_time, session_check_time, status, process_status, edit_status, super_admin_status, lock_status, user_type, password, password_change_status, password_change_date, add_by, edit_by, delete_by, verify_by, sent_checker_by, email_verified_at, remember_token, created_at, updated_at, deleted_at, verify_at, sent_checker_at, user_id, branch_id, actual_status, message, active_by, dactive_by, active_at, dactive_at, password_exp_date, block_status FROM users where Id=:insert_id";

        try {

            DB::statement($sql, ['insert_id' => $insert_id]);

            $insert_status= DB::getPdo()->lastInsertId();
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

            $total_records = User::leftJoin('branches', 'branches.id', '=', 'users.branch_id')
                ->leftJoin('departments', 'departments.id', '=', 'users.department')
                ->leftJoin('designations', 'designations.id', '=', 'users.designation')
                ->where('users.delete_status', 0)
                ->where(function ($query) use ($search_value) {
                    $query->where('users.name','like',"%".$search_value."%")
                        ->orWhere('users.user_id','like',"%".$search_value."%")
                        ->orWhere('users.mobile','like',"%".$search_value."%")
                        ->orWhere('users.email','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('departments.department_name','like',"%".$search_value."%")
                        ->orWhere('designations.designation_name','like',"%".$search_value."%")
                        ->orWhere('users.message','like',"%".$search_value."%");
                })
                ->count();

            $data = User::select('users.id','users.name', 'users.user_id', 'users.mobile', 'users.email', 'users.message','users.process_status', 'users.actual_status', 'users.status', 'users.edit_status', 'branches.branch_name', 'departments.department_name', 'designations.designation_name','users.block_status')
                ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
                ->leftJoin('departments', 'departments.id', '=', 'users.department')
                ->leftJoin('designations', 'designations.id', '=', 'users.designation')
                ->where('users.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('users.name','like',"%".$search_value."%")
                        ->orWhere('users.user_id','like',"%".$search_value."%")
                        ->orWhere('users.mobile','like',"%".$search_value."%")
                        ->orWhere('users.email','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('departments.department_name','like',"%".$search_value."%")
                        ->orWhere('designations.designation_name','like',"%".$search_value."%")
                        ->orWhere('users.message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = User::leftJoin('branches', 'branches.id', '=', 'users.branch_id')
                ->leftJoin('departments', 'departments.id', '=', 'users.department')
                ->leftJoin('designations', 'designations.id', '=', 'users.designation')
                ->where('users.delete_status', 0)
                ->count();

            $data = User::select('users.id','users.name', 'users.user_id', 'users.mobile', 'users.email', 'users.message','users.process_status', 'users.actual_status', 'users.status', 'users.edit_status', 'branches.branch_name', 'departments.department_name', 'designations.designation_name','users.block_status')
                ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
                ->leftJoin('departments', 'departments.id', '=', 'users.department')
                ->leftJoin('designations', 'designations.id', '=', 'users.designation')
                ->where('users.delete_status',0)
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

        $data = User::where('users.delete_status',0)
            ->select('users.*', 'branches.branch_name', 'departments.department_name', 'designations.designation_name', 'user_groups.group_name', 'maker.name as maker_by')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->leftJoin('departments', 'departments.id', '=', 'users.department')
            ->leftJoin('designations', 'designations.id', '=', 'users.designation')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'users.group_id')
            ->leftJoin('users as maker', 'maker.id', '=', 'users.sent_checker_by')
            ->where('users.id',$id)
            ->first();

        return $data;
    }

    public static function history($id){

        $data = DB::table('user_histories')
            ->leftJoin('branches', 'branches.id', '=', 'user_histories.branch_id')
            ->leftJoin('departments', 'departments.id', '=', 'user_histories.department')
            ->leftJoin('designations', 'designations.id', '=', 'user_histories.designation')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'user_histories.group_id')
            ->select('user_histories.*', 'branches.branch_name', 'departments.department_name', 'designations.designation_name', 'user_groups.group_name')
            ->where('user_histories.delete_status',0)
            ->where('user_histories.main_id',$id)
            ->where('user_histories.process_status',3)
            ->whereIn('user_histories.actual_status',[1,2,3])
            ->orderBy('user_histories.id','desc')
            ->get();

        return $data;
    }

    public static function update_data($request){

        $session_data = CommonController::get_session_data();

        $actual_status = 0;
        $des = '';

        if ($request->hasFile('user_photo')) {

            $file = $request->file('user_photo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->name);

            $request->hidden_user_photo = $title_name.'_logo_'.time().'.'.$extension;

            $file->move('uploads/user/',$request->hidden_user_photo);
        }

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){
            
            $update_data = [
                'user_id' => $request->user_id,
                'name' => $request->name,
                'group_id' => $request->group_id,
                'branch_id' => $request->branch_id,
                'department' => $request->department,
                'assign_department' => $request->assign_department,
                'designation' => $request->designation,
                'address' => $request->address,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'details' => $request->details,
                'user_photo' => $request->hidden_user_photo,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2
            ];

            $actual_status = 2;
            $des = 'Add-Edit Employee/User Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit Employee/User Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = User::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = User::insert_history($request->update_id);
            }
            else{

                $insert_history = User::update_history($request->update_id,$request);
            }

            $status = Common::add_user_activity_history('users',$request->update_id,$des,'',$actual_status,0);

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

    public static function update_history($insert_id=null,$request){

        $insert_status = 0;

        $sql = "INSERT INTO user_histories (main_id, name, email, mobile, address, details, department, assign_department, designation, group_id, date_of_birth, sex, blood_group, user_photo, verify_status, delete_status, user_session_time, session_check_time, status, process_status, edit_status, super_admin_status, lock_status, user_type, password, password_change_status, password_change_date, add_by, edit_by, delete_by, verify_by, sent_checker_by, email_verified_at, remember_token, created_at, updated_at, deleted_at, verify_at, sent_checker_at, user_id, branch_id, actual_status, message, active_by, dactive_by, active_at, dactive_at, password_exp_date, block_status) SELECT id, '$request->name', '$request->email', '$request->mobile', '$request->address', '$request->details', '$request->department', '$request->assign_department', '$request->designation', '$request->group_id', date_of_birth, sex, blood_group, '$request->hidden_user_photo', verify_status, delete_status, user_session_time, session_check_time, status, process_status, edit_status, super_admin_status, lock_status, user_type, password, password_change_status, password_change_date, add_by, edit_by, delete_by, verify_by, sent_checker_by, email_verified_at, remember_token, created_at, updated_at, deleted_at, verify_at, sent_checker_at, '$request->user_id', '$request->branch_id', actual_status, message, active_by, dactive_by, active_at, dactive_at, password_exp_date, block_status FROM users where Id=:insert_id";

        try {

            DB::statement($sql, ['insert_id' => $insert_id]);

            $insert_status = 1;
        }
        catch (\Exception $e) {

            $insert_status = 0;
        }

        return $insert_status;
    }

    public static function delete_data($id,$request){

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

        $data = User::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('users',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,'Delete User Information','',4,0);

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

    public static function get_single_history_data_by_id($id=null){

        $data = DB::table('user_histories')
            ->leftJoin('branches', 'branches.id', '=', 'user_histories.branch_id')
            ->leftJoin('departments', 'departments.id', '=', 'user_histories.department')
            ->leftJoin('designations', 'designations.id', '=', 'user_histories.designation')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'user_histories.group_id')
            ->select('user_histories.*', 'branches.branch_name', 'departments.department_name', 'designations.designation_name', 'user_groups.group_name')
            ->where('user_histories.delete_status',0)
            ->where('user_histories.main_id',$id)
            ->where('user_histories.process_status',0)
            ->orderBy('user_histories.id','desc')
            ->first();

        return $data;
    }

    public static function sent_to_checker($request, $id){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'sent_checker_by' => $session_data['id'],
            'sent_checker_at' => now(),
            'process_status' =>1,
        ];

        Common::db_transaction_on();

        $data = User::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,'Sent To Checker Employee/User Information','',$request->actual_status,1);

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

                $history_data = User::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['user_id'] = $history_data->user_id;
                    $update_data['name'] = $history_data->name;
                    $update_data['group_id'] = $history_data->group_id;
                    $update_data['branch_id'] = $history_data->branch_id;
                    $update_data['department'] = $history_data->department;
                    $update_data['assign_department'] = $history_data->assign_department;
                    $update_data['designation'] = $history_data->designation;
                    $update_data['address'] = $history_data->address;
                    $update_data['mobile'] = $history_data->mobile;
                    $update_data['email'] = $history_data->email;
                    $update_data['details'] = $history_data->details;
                    $update_data['user_photo'] = $history_data->user_photo;

                    if($request->actual_status==1 || $request->actual_status==2){
                        
                        $right_data = User::get_group_right_data_for_approve($history_data->group_id);

                        if(!empty($right_data)){

                            $now = now();
                            $sl = 0;

                            $right_arr = array();

                            foreach($right_data as $righ){

                                $right_arr[$sl]['user_id'] = $id;
                                $right_arr[$sl]['g_id'] = $righ->g_id;
                                $right_arr[$sl]['c_id'] = $righ->c_id;
                                $right_arr[$sl]['r_id'] = $righ->r_id;
                                $right_arr[$sl]['add_by'] = $session_data['id'];
                                $right_arr[$sl]['created_at'] = $now;

                                $sl++;
                            }

                            if(!empty($right_arr)){

                                DB::table('user_rights')->where('user_id', '=', $id)->delete();

                                DB::table('user_rights')->insert($right_arr);

                                Common::delete_cahce_data('menu_right:*');
                            }
                        }

                        $system_data = Common::get_system_setting_data();

                        $password_string = '';

                        if($system_data['default_password_type']==1){

                            $password_string = CommonController::get_dynamic_password($system_data['default_password_type'],$system_data['userid_min_length'],$system_data['password_max_length']);
                        }
                        else if($system_data['default_password_type']==2){

                            $password_string = $request->user_id;
                        }
                        else{

                            $password_string = '.';
                        }

                        $hash_password = CommonController::hash_password($password_string);

                        $password_history_status = Common::insert_password_history($id,$hash_password);

                        $password_exp_date = date('Y-m-d', strtotime(date('Y-m-d'). ' +'.$system_data['password_validity_days'].' days'));

                        $update_data['user_session_time'] = $system_data['session_time']*60;
                        $update_data['session_check_time'] = $system_data['session_check_time']*1000;
                        $update_data['password'] = $hash_password;
                        $update_data['password_exp_date'] = $password_exp_date;
                        $update_data['password_change_status'] = 0;
                        $update_data['password_change_date'] = now();
                        $update_data['status'] = 1;

                        $encrypt_data = CommonController::encrypt_data($history_data->main_id);

                        $status = MailController::sent_verify_email_with_password($history_data->email,$password_string,$encrypt_data,$history_data->name,$history_data->main_id);
                    }
                }
            }
            else if($request->actual_status==4){ 

                $update_data['delete_status'] = 1;
            }
            else if($request->actual_status==8){ 

                $history_data = User::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $right_data = User::get_right_data_for_approve($history_data->id);

                    if(!empty($right_data)){

                        $now = now();
                        $sl = 0;

                        $right_arr = array();

                        foreach($right_data as $righ){

                            $right_arr[$sl]['user_id'] = $id;
                            $right_arr[$sl]['g_id'] = $righ->g_id;
                            $right_arr[$sl]['c_id'] = $righ->c_id;
                            $right_arr[$sl]['r_id'] = $righ->r_id;
                            $right_arr[$sl]['add_by'] = $session_data['id'];
                            $right_arr[$sl]['created_at'] = $now;

                            $sl++;
                        }

                        if(!empty($right_arr)){

                            DB::table('user_rights')->where('user_id', '=', $id)->delete();

                            DB::table('user_rights')->insert($right_arr);

                            Common::delete_cahce_data('menu_right:*');
                        }
                    }
                }
            }
            
            $process_status = 3;
            $des = 'Approve Employee/User Information';
        }
        else{

            $process_status = 2;
            $des = 'Return Employee/User Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('users',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = User::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:users*');

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,$des,'',$request->actual_status,$process_status);

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

    public static function get_group_right_data_for_approve($group_id){

        $data = DB::table('user_group_rights')
            ->where('delete_status',0)
            ->select('*')
            ->where('group_id',$group_id)
            ->get();

        return $data;
    }

    public static function get_right_data_for_approve($history_id){

        $data = DB::table('user_right_histories')
            ->where('delete_status',0)
            ->select('*')
            ->where('history_id',$history_id)
            ->get();

        return $data;
    }

    public static function deactive($id,$request){

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

        $data = User::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('users',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:users*');

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,'Deactive Employee/User Information','',5,0);

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

    public static function active($id,$request){

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

        $data = User::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('users',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:users*');

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,'Active Employee/User Information','',6,0);

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

    public static function unlock($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'actual_status' => 10,
            'status' => 1,
            'process_status' =>0,
            'block_status' =>0,
            'message' => $request->unlock_reason,
            'active_by' => $session_data['id'],
            'active_at' =>now(),
        ];

        Common::db_transaction_on();

        $data = User::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('users',$id,10,0,$request->unlock_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:users*');

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,'Active Employee/User Information','',10,0);

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

    public static function reset_password($id,$request){

        $session_data = CommonController::get_session_data();
        $system_data = Common::get_system_setting_data();

        $password_string = '';

        if($system_data['default_password_type']==1){

            $password_string = CommonController::get_dynamic_password($system_data['default_password_type'],$system_data['userid_min_length'],$system_data['password_max_length']);
        }
        else if($system_data['default_password_type']==2){

            $password_string = $request->user_id;
        }
        else{

            $password_string = '.';
        }

        $hash_password = CommonController::hash_password($password_string);

        $password_exp_date = date('Y-m-d', strtotime(date('Y-m-d'). ' +'.$system_data['password_validity_days'].' days'));

        $update_data = [
            'actual_status' => 7,
            'password' => $hash_password,
            'process_status' =>0,
            'password_change_status' =>0,
            'password_change_date' => now(),
            'password_exp_date' => $password_exp_date,
            'message' => $request->resetpassword_reason,
            'active_by' => $session_data['id'],
            'active_at' =>now(),
        ];

        Common::db_transaction_on();

        $data = User::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('users',$id,7,0,$request->resetpassword_reason);

        $status = MailController::reset_password_mail($request->email,$password_string,$request->name,$id);
        
        if($data==true && $message_status==1){

            $insert_history = User::insert_history($id);

            $status = Common::add_user_activity_history('users',$id,'Reset Password Employee/User Information','',7,0);

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

    public static function set_right_user($id,$request){

        $session_data = CommonController::get_session_data();

        $update_data = [
            'actual_status' => 8,
            'process_status' =>0,
            'edit_by' => $session_data['id'],
            'updated_at' =>now(),
        ];

        Common::db_transaction_on();

        $data = User::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = User::insert_history($id);

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

                                $right_arr[$sl]['user_id'] = $id;
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

                    $status = DB::table('user_right_histories')->insert($right_arr);

                    if($status==true){

                        $status = Common::add_user_activity_history('users',$id,'Update Employee/User Right Information','',8,0);

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

    public static function get_user_by_email($email){

        $user_data = User::where('email',$email)
            ->where('delete_status',0)
            ->first();

        return $user_data;
    }

    public static function update_user_password($id,$password){

        $system_data = Common::get_system_setting_data();

        $password_exp_date = date('Y-m-d', strtotime(date('Y-m-d'). ' +'.$system_data['password_validity_days'].' days'));

        try {

            $password_exp_date_Key = CommonController::get_session_variable_name().'.password_exp_date';
            session()->put($password_exp_date_Key, $password_exp_date);

            $data_arr['password_change_status'] = 1;
            $data_arr['password_change_date'] = now();
            $data_arr['password'] = $password;
            $data_arr['password_exp_date'] = $password_exp_date;

            DB::table('users')
                ->where('id', $id)
                ->update($data_arr);

            return 1;

        } catch (\Exception $e) {
            
            return $e;
        }
    }

    public static function get_single_right_chnaged_history_data_by_id($id=null){

        $right_data = DB::table('user_right_histories')
            ->select('g_id', 'c_id', 'r_id', 'add_by', 'edit_by')
            ->where('status', 1)
            ->where('delete_status', 0)
            ->where('user_id', $id)
            ->where('history_id', function($query) use ($id) {
                $query->select('id')
                      ->from('user_histories')
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
                $right_arr[$data->g_id][$data->c_id][$data->r_id]['g_id'] = $data->g_id;
            }
        }

        return $right_arr;
    }
}