<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Designation extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $insert_data = [
            'designation_name' => $request->designation_name,
            'designation_code' => $request->designation_code,
            'designation_title' => $request->designation_title,
            'designation_details' => $request->designation_details,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        Common::db_transaction_on();

        $data = Designation::create($insert_data);

        if($data==true){

            $insert_history = Designation::insert_history($data->id);

            $status = Common::add_user_activity_history('designations',$data->id,'Add Designation Information','',1,0);

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

        $sql = "INSERT INTO designation_histories (main_id, designation_name, designation_code, designation_title, designation_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, designation_name, designation_code, designation_title, designation_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM designations where Id=:insert_id";

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

            $total_records = Designation::where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('designation_name','like',"%".$search_value."%")
                        ->orWhere('designation_code','like',"%".$search_value."%")
                        ->orWhere('message','like',"%".$search_value."%");
                })
                ->count();

            $data = Designation::select('id','designation_name', 'designation_code', 'message','process_status', 'actual_status', 'status', 'edit_status')
                ->where('delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('designation_name','like',"%".$search_value."%")
                        ->orWhere('designation_code','like',"%".$search_value."%")
                        ->orWhere('message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = Designation::where('delete_status', 0)->count();

            $data = Designation::select('id','designation_name', 'designation_code', 'message','process_status', 'actual_status', 'status', 'edit_status')
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

        $data = Designation::where('designations.delete_status',0)
            ->select('designations.*', 'maker.name as maker_by')
            ->leftJoin('users as maker', 'maker.id', '=', 'designations.sent_checker_by')
            ->where('designations.id',$id)
            ->first();

        return $data;
    }

    public static function update_data($request){

        $session_data = CommonController::get_session_data();

        $actual_status = 0;
        $des = '';

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){
            
            $update_data = [
                'designation_name' => $request->designation_name,
                'designation_code' => $request->designation_code,
                'designation_title' => $request->designation_title,
                'designation_details' => $request->designation_details,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2
            ];

            $actual_status = 2;
            $des = 'Add-Edit Designation Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit Designation Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = Designation::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = Designation::insert_history($request->update_id);
            }
            else{

                $insert_history = Designation::update_history($request->update_id,$request);
            }

            $status = Common::add_user_activity_history('designations',$request->update_id,$des,'',$actual_status,0);

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

        $sql = "INSERT INTO designation_histories (main_id, designation_name, designation_code, designation_title, designation_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id,'$request->designation_name', '$request->designation_code', '$request->designation_title', '$request->designation_details', delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM designations where Id=:insert_id";

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

        $data = Designation::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('designations',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = Designation::insert_history($id);

            $status = Common::add_user_activity_history('designations',$id,'Delete Designation Information','',4,0);

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

        $data = DB::table('designation_histories')
            ->where('delete_status',0)
            ->select('*')
            ->where('main_id',$id)
            ->where('process_status',0)
            ->orderBy('id','desc')
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

        $data = Designation::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = Designation::insert_history($id);

            $status = Common::add_user_activity_history('designations',$id,'Sent To Checker Designation Information','',$request->actual_status,1);

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

                $history_data = Designation::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['designation_name'] = $history_data->designation_name;
                    $update_data['designation_code'] = $history_data->designation_code;
                    $update_data['designation_title'] = $history_data->designation_title;
                    $update_data['designation_details'] = $history_data->designation_details;

                    if($request->actual_status==1 || $request->actual_status==2){

                        $update_data['status'] = 1;
                    }
                }
            }
            else if($request->actual_status==4){

                $update_data['delete_status'] = 1;
            }
            
            $process_status = 3;
            $des = 'Approve Designation Information';
        }
        else{

            $process_status = 2;
            $des = 'Return Designation Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('designations',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = Designation::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:designations*');

            $insert_history = Designation::insert_history($id);

            $status = Common::add_user_activity_history('designations',$id,$des,'',$request->actual_status,$process_status);

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

        $data = Designation::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('designations',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:designations*');

            $insert_history = Designation::insert_history($id);

            $status = Common::add_user_activity_history('designations',$id,'Deactive Designation Information','',5,0);

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

        $data = Designation::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('designations',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:designations*');

            $insert_history = Designation::insert_history($id);

            $status = Common::add_user_activity_history('designations',$id,'Active User Group Information','',6,0);

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

    public static function history($id){

        $data = DB::table('designation_histories')
            ->where('delete_status',0)
            ->select('*')
            ->where('main_id',$id)
            ->where('process_status',3)
            ->whereIn('actual_status',[1,2,3])
            ->orderBy('id','desc')
            ->get();

        return $data;
    }
}