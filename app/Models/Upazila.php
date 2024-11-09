<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Upazila extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public static function add_data($request){

        $session_data = CommonController::get_session_data();

        $insert_data = [
            'division_id' => $request->division_id,
            'district_id' => $request->district_id,
            'upazila_name' => $request->upazila_name,
            'upazila_code' => $request->upazila_code,
            'upazila_title' => $request->upazila_title,
            'upazila_details' => $request->upazila_details,
            'add_by' => $session_data['id'],
            'created_at' => now(),
            'process_status' => 0,
            'actual_status' => 1,
            'status' => 0
        ];

        Common::db_transaction_on();

        $data = Upazila::create($insert_data);

        if($data==true){

            $insert_history = Upazila::insert_history($data->id);

            $status = Common::add_user_activity_history('upazilas',$data->id,'Add Upazila Information','',1,0);

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

        $sql = "INSERT INTO upazila_histories (main_id, division_id, district_id, upazila_name, upazila_code, upazila_title, upazila_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, division_id,district_id, upazila_name, upazila_code, upazila_title, upazila_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM upazilas where Id=:insert_id";

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

            $total_records = Upazila::leftJoin('divisions', 'divisions.id', '=', 'upazilas.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'upazilas.district_id')
                ->where('upazilas.delete_status', 0)
                ->where(function ($query) use ($search_value) {
                    $query->where('upazilas.upazila_name','like',"%".$search_value."%")
                        ->orWhere('upazilas.upazila_code','like',"%".$search_value."%")
                        ->orWhere('divisions.division_name','like',"%".$search_value."%")
                        ->orWhere('districts.district_name','like',"%".$search_value."%")
                        ->orWhere('upazilas.message','like',"%".$search_value."%");
                })
                ->count();

            $data = Upazila::select('upazilas.id','upazilas.upazila_name', 'upazilas.upazila_code', 'upazilas.message','upazilas.process_status', 'upazilas.actual_status', 'upazilas.status', 'upazilas.edit_status', 'divisions.division_name', 'districts.district_name')
                ->leftJoin('divisions', 'divisions.id', '=', 'upazilas.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'upazilas.district_id')
                ->where('upazilas.delete_status',0)
                ->where(function ($query) use ($search_value) {
                    $query->where('upazilas.upazila_name','like',"%".$search_value."%")
                        ->orWhere('upazilas.upazila_code','like',"%".$search_value."%")
                        ->orWhere('divisions.division_name','like',"%".$search_value."%")
                        ->orWhere('districts.district_name','like',"%".$search_value."%")
                        ->orWhere('upazilas.message','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = Upazila::leftJoin('divisions', 'divisions.id', '=', 'upazilas.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'upazilas.district_id')
                ->where('upazilas.delete_status', 0)
                ->count();

            $data = Upazila::select('upazilas.id','upazilas.upazila_name', 'upazilas.upazila_code', 'upazilas.message','upazilas.process_status', 'upazilas.actual_status', 'upazilas.status', 'upazilas.edit_status', 'divisions.division_name', 'districts.district_name')
                ->leftJoin('divisions', 'divisions.id', '=', 'upazilas.division_id')
                ->leftJoin('districts', 'districts.id', '=', 'upazilas.district_id')
                ->where('upazilas.delete_status',0)
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

        $data = Upazila::where('upazilas.delete_status',0)
            ->select('upazilas.*','divisions.division_name','districts.district_name', 'maker.name as maker_by')
            ->leftJoin('divisions', 'divisions.id', '=', 'upazilas.division_id')
            ->leftJoin('districts', 'districts.id', '=', 'upazilas.district_id')
            ->leftJoin('users as maker', 'maker.id', '=', 'upazilas.sent_checker_by')
            ->where('upazilas.id',$id)
            ->first();

        return $data;
    }

    public static function history($id){

        $data = DB::table('upazila_histories')
            ->leftJoin('divisions', 'divisions.id', '=', 'upazila_histories.division_id')
            ->leftJoin('districts', 'districts.id', '=', 'upazila_histories.district_id')
            ->select('upazila_histories.*','divisions.division_name','districts.district_name')
            ->where('upazila_histories.delete_status',0)
            ->where('upazila_histories.main_id',$id)
            ->where('upazila_histories.process_status',3)
            ->whereIn('upazila_histories.actual_status',[1,2,3])
            ->orderBy('upazila_histories.id','desc')
            ->get();

        return $data;
    }

    public static function update_data($request){

        $session_data = CommonController::get_session_data();

        $actual_status = 0;
        $des = '';

        if(($request->actual_status==1 || $request->actual_status==2) && $request->process_status!=3){

            $update_data = [
                'division_id' => $request->division_id,
                'district_id' => $request->district_id,
                'upazila_name' => $request->upazila_name,
                'upazila_code' => $request->upazila_code,
                'upazila_title' => $request->upazila_title,
                'upazila_details' => $request->upazila_details,
                'add_by' => $session_data['id'],
                'created_at' => now(),
                'actual_status' => 2
            ];

            $actual_status = 2;
            $des = 'Add-Edit Upazila Information';
        }
        else{

            $update_data = [
                'edit_status' => 1,
                'actual_status' => 3,
                'edit_by' => $session_data['id'],
                'updated_at' => now(),
            ];

            $actual_status = 3;
            $des = 'Edit Upazila Information';
        }

        $update_data['process_status'] = 0;

        Common::db_transaction_on();

        $data = Upazila::where('id', $request->update_id)->update($update_data);

        if($data==true){

            if($actual_status==2){

                $insert_history = Upazila::insert_history($request->update_id);
            }
            else{

                $insert_history = Upazila::update_history($request->update_id,$request);
            }

            $status = Common::add_user_activity_history('upazilas',$request->update_id,$des,'',$actual_status,0);

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

        $sql = "INSERT INTO upazila_histories (main_id, division_id, district_id, upazila_name, upazila_code, upazila_title, upazila_details, delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at) SELECT id, '$request->division_id', '$request->district_id', '$request->upazila_name', '$request->upazila_code', '$request->upazila_title', '$request->upazila_details', delete_status, status, process_status, actual_status, edit_status, add_by, edit_by, delete_by, verify_by, sent_checker_by, created_at, updated_at, deleted_at, verify_at, sent_checker_at, message,active_by,dactive_by,active_at,dactive_at FROM upazilas where Id=:insert_id";

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

        $data = Upazila::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('upazilas',$id,4,0,$request->delete_reason);
        
        if($data==true && $message_status==1){

            $insert_history = Upazila::insert_history($id);

            $status = Common::add_user_activity_history('upazilas',$id,'Delete Upazila Information','',4,0);

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

        $data = DB::table('upazila_histories')
            ->leftJoin('divisions', 'divisions.id', '=', 'upazila_histories.division_id')
            ->leftJoin('districts', 'districts.id', '=', 'upazila_histories.district_id')
            ->select('upazila_histories.*','divisions.division_name','districts.district_name')
            ->where('upazila_histories.delete_status',0)
            ->where('upazila_histories.main_id',$id)
            ->where('upazila_histories.process_status',0)
            ->orderBy('upazila_histories.id','desc')
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

        $data = Upazila::where('id', $id)->update($update_data);

        if($data==true){

            $insert_history = Upazila::insert_history($id);

            $status = Common::add_user_activity_history('upazilas',$id,'Sent To Checker Upazila Information','',$request->actual_status,1);

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

                $history_data = Upazila::get_single_history_data_by_id($id);

                if(!empty($history_data)){

                    $update_data['division_id'] = $history_data->division_id;
                    $update_data['district_id'] = $history_data->district_id;
                    $update_data['upazila_name'] = $history_data->upazila_name;
                    $update_data['upazila_code'] = $history_data->upazila_code;
                    $update_data['upazila_title'] = $history_data->upazila_title;
                    $update_data['upazila_details'] = $history_data->upazila_details;

                    if($request->actual_status==1 || $request->actual_status==2){

                        $update_data['status'] = 1;
                    }
                }
            }
            else if($request->actual_status==4){

                $update_data['delete_status'] = 1;
            }
            
            $process_status = 3;
            $des = 'Approve Upazila Information';
        }
        else{

            $process_status = 2;
            $des = 'Return Upazila Information';

            $update_data['message'] = $request->return_reason;

            $message_status = Common::insert_return_message('upazilas',$id,$request->actual_status,$process_status,$request->return_reason);
        }

        $update_data['verify_by'] = $session_data['id'];
        $update_data['verify_at'] = now();
        $update_data['process_status'] = $process_status;

        $data = Upazila::where('id', $id)->update($update_data);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:upazilas*');

            $insert_history = Upazila::insert_history($id);

            $status = Common::add_user_activity_history('upazilas',$id,$des,'',$request->actual_status,$process_status);

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

        $data = Upazila::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('upazilas',$id,5,0,$request->deactive_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:upazilas*');

            $insert_history = Upazila::insert_history($id);

            $status = Common::add_user_activity_history('upazilas',$id,'Deactive Upazila Information','',5,0);

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

        $data = Upazila::where('id', $id)->update($update_data);

        $message_status = Common::insert_return_message('upazilas',$id,6,0,$request->active_reason);
        
        if($data==true && $message_status==1){

            Common::delete_cahce_data('dropdown:upazilas*');

            $insert_history = Upazila::insert_history($id);

            $status = Common::add_user_activity_history('upazilas',$id,'Active Upazila Information','',6,0);

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