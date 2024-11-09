<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Report extends Model
{
    protected $guarded = [];

    use HasFactory;

    public static function user_activity_report($request){

        $from_date = input_db_fromate($request->from_date);
        $to_date = input_db_fromate($request->to_date);

        $where_arr = array();

        if ($request->user_id!='' && $request->user_id!=null) {
            
            $where_arr['a.add_by'] = $request->user_id;
        }

        if ($request->right_group_id!='' && $request->right_group_id!=null) {
            
            $where_arr['a.group_id'] = $request->right_group_id;
        }

        if ($request->right_category_id!='' && $request->right_category_id!=null) {
            
            $where_arr['a.category_id'] = $request->right_category_id;
        }

        if ($request->right_id!='' && $request->right_id!=null) {
            
            $where_arr['a.right_id'] = $request->right_id;
        }

        if ($request->actual_process_status_id!='' && $request->actual_process_status_id!=null) {
            
            $where_arr['a.actual_status'] = $request->actual_process_status_id;
        }

        $data = DB::table('activity_histories as a')
            ->leftJoin('users as b', 'b.id', '=', 'a.add_by')
            ->leftJoin('right_groups as c', 'c.id', '=', 'a.group_id')
            ->leftJoin('right_categories as d', 'd.id', '=', 'a.category_id')
            ->leftJoin('right_details as e', 'e.id', '=', 'a.right_id')
            ->leftJoin('actual_process_statuses as f', 'f.id', '=', 'a.actual_status')
            ->select('a.activity','a.ip_address','a.created_at','a.table_id','a.table_name','b.name','b.user_id','c.name as group_name','d.c_name as category_name','e.r_name as right_name','f.status_name as status_name')
            ->where($where_arr)
            ->whereDate('a.created_at', '>=', $from_date)
            ->whereDate('a.created_at', '<=', $to_date)
            ->orderBy('a.id','desc')
            ->get()->toArray();

        return $data;
    }

    public static function user_log_report($request){

        $from_date = input_db_fromate($request->from_date);
        $to_date = input_db_fromate($request->to_date);

        $where_arr = array();

        if ($request->user_id!='' && $request->user_id!=null) {
            
            $where_arr['a.user_id'] = $request->user_id;
        }

        $data = DB::table('user_log_histories as a')
            ->leftJoin('users as b', 'b.id', '=', 'a.user_id')
            ->select('a.ip_address','a.log_in_time','a.log_out_time','a.last_active_time','a.user_agent','a.log_out_status','a.force_status','b.name as user_name', 'b.user_id as user_id')
            ->where($where_arr)
            ->whereDate('a.log_in_time', '>=', $from_date)
            ->whereDate('a.log_in_time', '<=', $to_date)
            ->orderBy('a.id','desc')
            ->get()->toArray();

        return $data;
    }

    public static function user_list_report($request){
        
        $where_arr = array();

        if ($request->status!='' && $request->status!=null) {
            
            $where_arr['a.status'] = $request->status;
        }

        $data = DB::table('users as a')
            ->select('a.*', 'branches.branch_name', 'departments.department_name', 'designations.designation_name', 'user_groups.group_name')
            ->leftJoin('branches', 'branches.id', '=', 'a.branch_id')
            ->leftJoin('departments', 'departments.id', '=', 'a.department')
            ->leftJoin('designations', 'designations.id', '=', 'a.designation')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'a.group_id')
            ->where($where_arr)
            ->where('a.delete_status',0)
            ->get()->toArray();

        return $data;
    }

    public static function user_token_report($request){

        $draw = $request->draw;
        $row = $request->start;
        $row_per_page  = $request->length;

        $column_index  = $request->order[0]['column'];
        $column_name = $request->columns[$column_index]['data'];
        $column_ort_order = $request->order[0]['dir'];

        $from_date = input_db_fromate($request->from_date);
        $to_date = input_db_fromate($request->to_date);

        $user_id_arr = array();

        if(is_array($request->user_id)){
            foreach ($request->user_id as $user_id) {

                if($user_id!=',' && $user_id!=''){
                
                    $user_id_arr[] = $user_id;
                }
            }
        }

        $total = 0;
        $result = array();
        $response = array();

        $total_records = Token::leftJoin('branches', 'branches.id', '=', 'tokens.branch_id')
            ->leftJoin('floors', 'floors.id', '=', 'tokens.floor_id')
            ->leftJoin('services', 'services.id', '=', 'tokens.service_id')
            ->leftJoin('counters', 'counters.id', '=', 'tokens.counter_id')
            ->leftJoin('users', 'users.id', '=', 'tokens.user_id')
            ->where('tokens.delete_status', 0)
            ->whereDate('tokens.created_at', '>=', $from_date)
            ->whereDate('tokens.created_at', '<=', $to_date);

        if(!empty($user_id_arr)){
                
            $total_records->whereIn('tokens.user_id',$user_id_arr);
        }

        $total =  $total_records->count();

        $data = Token::select('tokens.*','branches.branch_name', 'floors.floor_name', 'services.service_name','counters.counter_name','users.name as user_name')
            ->leftJoin('branches', 'branches.id', '=', 'tokens.branch_id')
            ->leftJoin('floors', 'floors.id', '=', 'tokens.floor_id')
            ->leftJoin('services', 'services.id', '=', 'tokens.service_id')
            ->leftJoin('counters', 'counters.id', '=', 'tokens.counter_id')
            ->leftJoin('users', 'users.id', '=', 'tokens.user_id')
            ->where('tokens.delete_status',0)
            ->whereDate('tokens.created_at', '>=', $from_date)
            ->whereDate('tokens.created_at', '<=', $to_date);

        if(!empty($user_id_arr)){
                
            $data->whereIn('tokens.user_id',$user_id_arr);
        }

        $result = $data->orderBy($column_name,$column_ort_order)
            ->offset($row)
            ->limit($row_per_page)
            ->get();

        $response['draw'] = $draw;
        $response['iTotalRecords'] = $total;
        $response['iTotalDisplayRecords'] = $total;
        $response['aaData'] = $result;
        $response['csrf_token'] = csrf_token();

        return $response;
    }
}