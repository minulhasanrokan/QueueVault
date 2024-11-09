<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Branch extends Model
{
    protected $guarded = [];

    use HasFactory;

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $ad_code = '';
        $ad_branch_id = '0';

        if($request->ad_status==1){

            $ad_code = $request->ad_code;
        }
        else{

            $ad_branch_id = $request->ad_branch_id;
        }

        $insert_data = [
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_id' => $request->upazila_id,
            'assign_service' => $request->assign_service,
            'branch_name' => $request->branch_name,
            'branch_code' => $request->branch_code,
            'branch_title' => $request->branch_title,
            'branch_details' => $request->branch_details,
            'ad_status' => $request->ad_status,
            'ad_branch_id' => $ad_branch_id,
            'ad_code' => $ad_code,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        Common::db_transaction_on();

        $data = Branch::create($insert_data);

        if($data==true){

            $insert_history = Branch::insert_history($data->id);

            $status = Common::add_user_activity_history('branches',$data->id,'Add Branch Information','',1,0);

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

        $sql = "INSERT INTO branch_histories (main_id, division_id, district_id, upazila_id, assign_service, branch_name, branch_code, branch_title, branch_details, ad_code, ad_branch_id, ad_status, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, division_id,district_id, upazila_id, assign_service, branch_name, branch_code, branch_title, branch_details, ad_code, ad_branch_id, ad_status, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM branches where Id=:insert_id";

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

            $total_records = Branch::leftJoin('divisions', 'divisions.id', '=', 'branches.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'branches.district_id')
                ->leftJoin('upazilas', 'upazilas.id', '=', 'branches.upazila_id')
                ->where('branches.delete_status', 0)
                ->where(function ($query) use ($search_value) {
                    $query->where('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('branches.branch_code','like',"%".$search_value."%")
                        ->orWhere('divisions.division_name','like',"%".$search_value."%")
                        ->orWhere('districts.district_name','like',"%".$search_value."%")
                        ->orWhere('upazilas.upazila_name','like',"%".$search_value."%")
                        ->orWhere('branches.message','like',"%".$search_value."%");
                })
                ->count();

            $data = Branch::select('branches.id','branches.branch_name', 'branches.branch_code', 'branches.message','branches.process_status', 'branches.actual_status', 'branches.status', 'branches.edit_status', 'divisions.division_name', 'districts.district_name', 'upazilas.upazila_name')
                ->leftJoin('divisions', 'divisions.id', '=', 'branches.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'branches.district_id')
                ->leftJoin('upazilas', 'upazilas.id', '=', 'branches.upazila_id')
                ->where('branches.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('branches.branch_code','like',"%".$search_value."%")
                        ->orWhere('divisions.division_name','like',"%".$search_value."%")
                        ->orWhere('districts.district_name','like',"%".$search_value."%")
                        ->orWhere('upazilas.upazila_name','like',"%".$search_value."%")
                        ->orWhere('branches.message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = Branch::leftJoin('divisions', 'divisions.id', '=', 'branches.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'branches.district_id')
                ->leftJoin('upazilas', 'upazilas.id', '=', 'branches.upazila_id')
                ->where('branches.delete_status', 0)
                ->count();

            $data = Branch::select('branches.id','branches.branch_name', 'branches.branch_code', 'branches.message','branches.process_status', 'branches.actual_status', 'branches.status', 'branches.edit_status', 'divisions.division_name', 'districts.district_name', 'upazilas.upazila_name')
                ->leftJoin('divisions', 'divisions.id', '=', 'branches.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'branches.district_id')
                ->leftJoin('upazilas', 'upazilas.id', '=', 'branches.upazila_id')
                ->where('branches.delete_status',0)
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

        $data = Branch::where('branches.delete_status',0)
            ->select('branches.*','divisions.division_name','districts.district_name','upazilas.upazila_name', 'maker.name as maker_by')
            ->leftJoin('divisions', 'divisions.id', '=', 'branches.division_id')
            ->leftJoin('districts', 'districts.id', '=', 'branches.district_id')
            ->leftJoin('upazilas', 'upazilas.id', '=', 'branches.upazila_id')
            ->leftJoin('users as maker', 'maker.id', '=', 'branches.sent_checker_by')
            ->where('branches.id',$id)
            ->first();

        return $data;
    }

    public static function history($id){

        $data = DB::table('branch_histories')
            ->leftJoin('divisions', 'divisions.id', '=', 'branch_histories.division_id')
            ->leftJoin('districts', 'districts.id', '=', 'branch_histories.district_id')
            ->leftJoin('upazilas', 'upazilas.id', '=', 'branch_histories.upazila_id')
            ->select('branch_histories.*','divisions.division_name','districts.district_name','upazilas.upazila_name')
            ->where('branch_histories.delete_status',0)
            ->where('branch_histories.main_id',$id)
            ->where('branch_histories.process_status',3)
            ->whereIn('branch_histories.actual_status',[1,2,3])
            ->orderBy('branch_histories.id','desc')
            ->get();

        return $data;
    }

    public static function update_data($request){

        $session_data = CommonController::get_session_data();

        $actual_status = 0;
        $des = '';

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){

            $ad_code = '';
            $ad_branch_id = '0';

            if($request->ad_status==1){

                $ad_code = $request->ad_code;
            }
            else{

                $ad_branch_id = $request->ad_branch_id;
            }

            $update_data = [
                'division_id' => $request->division_id,
                'district_id' => $request->district_id,
                'upazila_id' => $request->upazila_id,
                'assign_service' => $request->assign_service,
                'branch_name' => $request->branch_name,
                'branch_code' => $request->branch_code,
                'branch_title' => $request->branch_title,
                'branch_details' => $request->branch_details,
                'ad_status' => $request->ad_status,
                'ad_branch_id' => $ad_branch_id,
                'ad_code' => $ad_code,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2
            ];

            $actual_status = 2;
            $des = 'Add-Edit Branch Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit Branch Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = Branch::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = Branch::insert_history($request->update_id);
            }
            else{

                $insert_history = Branch::update_history($request->update_id,$request);
            }

            $status = Common::add_user_activity_history('branches',$request->update_id,$des,'',$actual_status,0);

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

        $ad_code = '';
        $ad_branch_id = '0';

        if($request->ad_status==1){

            $ad_code = $request->ad_code;
        }
        else{

            $ad_branch_id = $request->ad_branch_id;
        }

        $sql = "INSERT INTO branch_histories (main_id, division_id, district_id, upazila_id, assign_service, branch_name, branch_code, branch_title, branch_details, ad_code, ad_branch_id, ad_status, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, '$request->division_id', '$request->district_id', '$request->upazila_id', '$request->assign_service', '$request->branch_name', '$request->branch_code', '$request->branch_title', '$request->branch_details', '$ad_code', '$ad_branch_id', '$request->ad_status', delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM branches where Id=:insert_id";

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

        $data = Branch::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('branches',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = Branch::insert_history($id);

            $status = Common::add_user_activity_history('branches',$id,'Delete Branch Information','',4,0);

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

        $data = DB::table('branch_histories')
            ->leftJoin('divisions', 'divisions.id', '=', 'branch_histories.division_id')
            ->leftJoin('districts', 'districts.id', '=', 'branch_histories.district_id')
            ->leftJoin('upazilas', 'upazilas.id', '=', 'branch_histories.upazila_id')
            ->select('branch_histories.*','divisions.division_name','districts.district_name','upazilas.upazila_name')
            ->where('branch_histories.delete_status',0)
            ->where('branch_histories.main_id',$id)
            ->where('branch_histories.process_status',0)
            ->orderBy('branch_histories.id','desc')
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

        $data = Branch::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = Branch::insert_history($id);

            $status = Common::add_user_activity_history('branches',$id,'Sent To Checker Branch Information','',$request->actual_status,1);

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

                $history_data = Branch::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['division_id'] = $history_data->division_id;
                    $update_data['district_id'] = $history_data->district_id;
                    $update_data['upazila_id'] = $history_data->upazila_id;
                    $update_data['assign_service'] = $history_data->assign_service;
                    $update_data['branch_name'] = $history_data->branch_name;
                    $update_data['branch_code'] = $history_data->branch_code;
                    $update_data['branch_title'] = $history_data->branch_title;
                    $update_data['branch_details'] = $history_data->branch_details;
                    $update_data['ad_code'] = $history_data->ad_code;
                    $update_data['ad_branch_id'] = $history_data->ad_branch_id;
                    $update_data['ad_status'] = $history_data->ad_status;

                    if($request->actual_status==1 || $request->actual_status==2){

                        $update_data['status'] = 1;
                    }
                }
            }
            else if($request->actual_status==4){

                $update_data['delete_status'] = 1;
            }
            
            $process_status = 3;
            $des = 'Approve Branch Information';
        }
        else{

            $process_status = 2;
            $des = 'Return Branch Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('branches',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = Branch::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:branches*');

            $insert_history = Branch::insert_history($id);

            $status = Common::add_user_activity_history('branches',$id,$des,'',$request->actual_status,$process_status);

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

        $data = Branch::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('branches',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:branches*');

            $insert_history = Branch::insert_history($id);

            $status = Common::add_user_activity_history('branches',$id,'Deactive Branch Information','',5,0);

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

        $data = Branch::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('branches',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:branches*');

            $insert_history = Branch::insert_history($id);

            $status = Common::add_user_activity_history('branches',$id,'Active Branch Information','',6,0);

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