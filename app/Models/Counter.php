<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Counter extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $insert_data = [
            'branch_id' => $request->branch_id,
            'floor_id' => $request->floor_id,
            'assign_service' => $request->assign_service,
            'counter_name' => $request->counter_name,
            'counter_code' => $request->counter_code,
            'counter_title' => $request->counter_title,
            'counter_details' => $request->counter_details,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        Common::db_transaction_on();

        $data = Counter::create($insert_data);

        if($data==true){

            $insert_history = Counter::insert_history($data->id);

            $status = Common::add_user_activity_history('counters',$data->id,'Add Counter Information','',1,0);

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

        $sql = "INSERT INTO counter_histories (main_id, branch_id, floor_id, assign_service, counter_name, counter_code, counter_title, counter_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, branch_id, floor_id, assign_service, counter_name, counter_code, counter_title, counter_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM counters where Id=:insert_id";

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

            $total_records = Counter::leftJoin('branches', 'branches.id', '=', 'counters.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'counters.floor_id')
                ->where('counters.delete_status', 0)
                ->where(function ($query) use ($search_value) {
                    $query->where('counters.counter_name','like',"%".$search_value."%")
                        ->orWhere('counters.counter_code','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('floors.floor_name','like',"%".$search_value."%")
                        ->orWhere('counters.message','like',"%".$search_value."%");
                })
                ->count();

            $data = Counter::select('counters.id','counters.counter_name', 'counters.counter_code', 'counters.message','counters.process_status', 'counters.actual_status', 'counters.status', 'counters.edit_status', 'branches.branch_name', 'floors.floor_name')
                ->leftJoin('branches', 'branches.id', '=', 'counters.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'counters.floor_id')
                ->where('counters.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('counters.counter_name','like',"%".$search_value."%")
                        ->orWhere('counters.counter_code','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('floors.floor_name','like',"%".$search_value."%")
                        ->orWhere('counters.message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = Counter::leftJoin('branches', 'branches.id', '=', 'counters.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'counters.floor_id')
                ->where('counters.delete_status', 0)
                ->count();

            $data = Counter::select('counters.id','counters.counter_name', 'counters.counter_code', 'counters.message','counters.process_status', 'counters.actual_status', 'counters.status', 'counters.edit_status', 'branches.branch_name', 'floors.floor_name')
                ->leftJoin('branches', 'branches.id', '=', 'counters.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'counters.floor_id')
                ->where('counters.delete_status',0)
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

        $data = Counter::where('counters.delete_status',0)
            ->select('counters.*','branches.branch_name', 'maker.name as maker_by', 'floors.floor_name', 'floors.assign_service as floor_assign_service')
            ->leftJoin('branches', 'branches.id', '=', 'counters.branch_id')
            ->leftJoin('floors', 'floors.id', '=', 'counters.floor_id')
            ->leftJoin('users as maker', 'maker.id', '=', 'counters.sent_checker_by')
            ->where('counters.id',$id)
            ->first();

        return $data;
    }

    public static function history($id){

        $data = DB::table('counter_histories')
            ->leftJoin('branches', 'branches.id', '=', 'counter_histories.branch_id')
            ->leftJoin('floors', 'floors.id', '=', 'counter_histories.floor_id')
            ->select('counter_histories.*', 'branches.branch_name', 'floors.floor_name')
            ->where('counter_histories.delete_status',0)
            ->where('counter_histories.main_id',$id)
            ->where('counter_histories.process_status',3)
            ->whereIn('counter_histories.actual_status',[1,2,3])
            ->orderBy('counter_histories.id','desc')
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
                'floor_id' => $request->floor_id,
                'assign_service' => $request->assign_service,
                'counter_name' => $request->counter_name,
                'counter_code' => $request->counter_code,
                'counter_title' => $request->counter_title,
                'counter_details' => $request->counter_details,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2
            ];

            $actual_status = 2;
            $des = 'Add-Edit Counter Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit Counter Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = Counter::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = Counter::insert_history($request->update_id);
            }
            else{

                $insert_history = Counter::update_history($request->update_id,$request);
            }

            $status = Common::add_user_activity_history('counters',$request->update_id,$des,'',$actual_status,0);

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

        $sql = "INSERT INTO counter_histories (main_id, branch_id, floor_id, assign_service, counter_name, counter_code, counter_title, counter_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, '$request->branch_id', '$request->floor_id', '$request->assign_service', '$request->counter_name', '$request->counter_code', '$request->counter_title', '$request->counter_details', delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM counters where Id=:insert_id";

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

        $data = Counter::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('counters',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = Counter::insert_history($id);

            $status = Common::add_user_activity_history('counters',$id,'Delete Counter Information','',4,0);

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

        $data = DB::table('counter_histories')
            ->leftJoin('branches', 'branches.id', '=', 'counter_histories.branch_id')
            ->leftJoin('floors', 'floors.id', '=', 'counter_histories.floor_id')
            ->select('counter_histories.*','branches.branch_name','floors.floor_name')
            ->where('counter_histories.delete_status',0)
            ->where('counter_histories.main_id',$id)
            ->where('counter_histories.process_status',0)
            ->orderBy('counter_histories.id','desc')
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

        $data = Counter::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = Counter::insert_history($id);

            $status = Common::add_user_activity_history('counters',$id,'Sent To Checker Counter Information','',$request->actual_status,1);

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

                $history_data = Counter::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['branch_id'] = $history_data->branch_id;
                    $update_data['floor_id'] = $history_data->floor_id;
                    $update_data['counter_name'] = $history_data->counter_name;
                    $update_data['counter_code'] = $history_data->counter_code;
                    $update_data['counter_title'] = $history_data->counter_title;
                    $update_data['counter_details'] = $history_data->counter_details;
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
            $des = 'Approve Counter Information';
        }
        else{

            $process_status = 2;
            $des = 'Return Counter Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('counters',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = Counter::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:counters*');

            $insert_history = Counter::insert_history($id);

            $status = Common::add_user_activity_history('counters',$id,$des,'',$request->actual_status,$process_status);

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

        $data = Counter::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('counters',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:counters*');

            $insert_history = Counter::insert_history($id);

            $status = Common::add_user_activity_history('counters',$id,'Deactive Counter Information','',5,0);

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

        $data = Counter::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('counters',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:counters*');

            $insert_history = Counter::insert_history($id);

            $status = Common::add_user_activity_history('counters',$id,'Active Counter Information','',6,0);

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