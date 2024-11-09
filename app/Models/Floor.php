<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Floor extends Model
{
    protected $guarded = [];

    use HasFactory;

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $insert_data = [
            'branch_id' => $request->branch_id,
            'assign_service' => $request->assign_service,
            'floor_name' => $request->floor_name,
            'floor_code' => $request->floor_code,
            'floor_title' => $request->floor_title,
            'floor_details' => $request->floor_details,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        Common::db_transaction_on();

        $data = Floor::create($insert_data);

        if($data==true){

            $insert_history = Floor::insert_history($data->id);

            $status = Common::add_user_activity_history('floors',$data->id,'Add Floor Information','',1,0);

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

        $sql = "INSERT INTO floor_histories (main_id, branch_id, assign_service, floor_name, floor_code, floor_title, floor_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, branch_id, assign_service, floor_name, floor_code, floor_title, floor_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM floors where Id=:insert_id";

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

            $total_records = Floor::leftJoin('branches', 'branches.id', '=', 'floors.branch_id')
                ->where('floors.delete_status', 0)
                ->where(function ($query) use ($search_value) {
                    $query->where('floors.floor_name','like',"%".$search_value."%")
                        ->orWhere('floors.floor_code','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('floors.message','like',"%".$search_value."%");
                })
                ->count();

            $data = Floor::select('floors.id','floors.floor_name', 'floors.floor_code', 'floors.message','floors.process_status', 'floors.actual_status', 'floors.status', 'floors.edit_status', 'branches.branch_name')
                ->leftJoin('branches', 'branches.id', '=', 'floors.branch_id')
                ->where('floors.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('floors.floor_name','like',"%".$search_value."%")
                        ->orWhere('floors.floor_code','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('floors.message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = Floor::leftJoin('branches', 'branches.id', '=', 'floors.branch_id')
                ->where('floors.delete_status', 0)
                ->count();

            $data = Floor::select('floors.id','floors.floor_name', 'floors.floor_code', 'floors.message','floors.process_status', 'floors.actual_status', 'floors.status', 'floors.edit_status', 'branches.branch_name')
                ->leftJoin('branches', 'branches.id', '=', 'floors.branch_id')
                ->where('floors.delete_status',0)
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

        $data = Floor::where('floors.delete_status',0)
            ->select('floors.*','branches.branch_name', 'maker.name as maker_by')
            ->leftJoin('branches', 'branches.id', '=', 'floors.branch_id')
            ->leftJoin('users as maker', 'maker.id', '=', 'floors.sent_checker_by')
            ->where('floors.id',$id)
            ->first();

        return $data;
    }

    public static function history($id){

        $data = DB::table('floor_histories')
            ->leftJoin('branches', 'branches.id', '=', 'floor_histories.branch_id')
            ->select('floor_histories.*','branches.branch_name')
            ->where('floor_histories.delete_status',0)
            ->where('floor_histories.main_id',$id)
            ->where('floor_histories.process_status',3)
            ->whereIn('floor_histories.actual_status',[1,2,3])
            ->orderBy('floor_histories.id','desc')
            ->get();

        return $data;
    }

    public static function update_data($request){

        $session_data = CommonController::get_session_data();

        $actual_status = 0;
        $des = '';

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){

            $update_data = [
                'branch_id' => $request->branch_id,
                'assign_service' => $request->assign_service,
                'floor_name' => $request->floor_name,
                'floor_code' => $request->floor_code,
                'floor_title' => $request->floor_title,
                'floor_details' => $request->floor_details,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2
            ];

            $actual_status = 2;
            $des = 'Add-Edit Floor Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit Floor Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = Floor::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = Floor::insert_history($request->update_id);
            }
            else{

                $insert_history = Floor::update_history($request->update_id,$request);
            }

            $status = Common::add_user_activity_history('floors',$request->update_id,$des,'',$actual_status,0);

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

        $sql = "INSERT INTO floor_histories (main_id, branch_id, assign_service, floor_name, floor_code, floor_title, floor_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, '$request->branch_id', '$request->assign_service', '$request->floor_name', '$request->floor_code', '$request->floor_title', '$request->floor_details', delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM floors where Id=:insert_id";

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

        $data = Floor::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('floors',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = Floor::insert_history($id);

            $status = Common::add_user_activity_history('floors',$id,'Delete Floor Information','',4,0);

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

        $data = DB::table('floor_histories')
            ->leftJoin('branches', 'branches.id', '=', 'floor_histories.branch_id')
            ->select('floor_histories.*','branches.branch_name')
            ->where('floor_histories.delete_status',0)
            ->where('floor_histories.main_id',$id)
            ->where('floor_histories.process_status',0)
            ->orderBy('floor_histories.id','desc')
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

        $data = Floor::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = Floor::insert_history($id);

            $status = Common::add_user_activity_history('floors',$id,'Sent To Checker Floor Information','',$request->actual_status,1);

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

                $history_data = Floor::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['branch_id'] = $history_data->branch_id;
                    $update_data['floor_name'] = $history_data->floor_name;
                    $update_data['floor_code'] = $history_data->floor_code;
                    $update_data['floor_title'] = $history_data->floor_title;
                    $update_data['floor_details'] = $history_data->floor_details;
                    $update_data['assign_service'] = $history_data->assign_service;

                    if($request->actual_status==1 || $request->actual_status==2){

                        $update_data['status'] = 1;
                    }
                }
            }
            else if($request->actual_status==4){

                $update_data['delete_status'] = 1;
            }
            
            $process_status = 3;
            $des = 'Approve Floor Information';
        }
        else{

            $process_status = 2;
            $des = 'Return Floor Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('floors',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = Floor::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:floors*');

            $insert_history = Floor::insert_history($id);

            $status = Common::add_user_activity_history('floors',$id,$des,'',$request->actual_status,$process_status);

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

        $data = Floor::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('floors',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:floors*');

            $insert_history = Floor::insert_history($id);

            $status = Common::add_user_activity_history('floors',$id,'Deactive Floor Information','',5,0);

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

        $data = Floor::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('floors',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:floors*');

            $insert_history = Floor::insert_history($id);

            $status = Common::add_user_activity_history('floors',$id,'Active Floor Information','',6,0);

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