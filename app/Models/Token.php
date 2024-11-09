<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class Token extends Model
{
    protected $guarded = [];

    use HasFactory;

    public static function get_service_for_registration($token_data){

        $service_data = array();

        if($token_data[0]=='' && $token_data[1]=='' && $token_data[2]=='')
        {
            $service_data = DB::table('services as a')
                ->select('a.id','a.service_name')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->get();
        }
        else if($token_data[0]!='' && $token_data[1]=='' && $token_data[2]==''){

            $branch_data = DB::table('branches as a')
                ->select('a.id','a.assign_service')
                ->where('id',$token_data[0])
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->first();

            $assign_service_arr = explode(",", $branch_data->assign_service);

            $service_data = DB::table('services as a')
                ->select('a.id','a.service_name')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('id', $assign_service_arr)
                ->get();
        }
        else if($token_data[0]!='' && $token_data[1]!='' && $token_data[2]==''){

            $branch_data = DB::table('floors as a')
                ->select('a.id','a.assign_service')
                ->where('id',$token_data[1])
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->first();

            $assign_service_arr = explode(",", $branch_data->assign_service);

            $service_data = DB::table('services as a')
                ->select('a.id','a.service_name')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('id', $assign_service_arr)
                ->get();
        }
        else if($token_data[0]!='' && $token_data[1]!='' && $token_data[2]!=''){

            $assign_service_arr = explode(",", $token_data[2]);

            $service_data = DB::table('services as a')
                ->select('a.id','a.service_name')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('id', $assign_service_arr)
                ->get();
        }

        return $service_data;
    }

    public static function generate_token($request){

        $branch_id = $request->branch_id;
        $floor_id = $request->floor_id;
        $service_id = $request->service_id;

        $service_data = Token::get_service_details($service_id);

        $reference_no = Token::generate_reference_number($branch_id, $floor_id, $service_id);

        $serial_arr = Token::generate_serial_number($branch_id, $floor_id, $service_id);

        $customer_waiting = Token::get_customer_waiting_number($branch_id, $floor_id, $service_id);

        $serial_number = $serial_arr['serial_number']==1?$serial_arr['serial_number']+$service_data->service_start_number:$serial_arr['serial_number'];

        $token_number = $service_data->service_letter.'-'.$serial_number;

        $insert_data = array(
            'name' => '',
            'email' => '',
            'phone' => '',
            'branch_id' => $branch_id,
            'floor_id' => $floor_id,
            'service_id' => $service_id,
            'serial' => $reference_no['serial'],
            'reference_no' => $reference_no['reference_no'],
            'position' => $serial_arr['position'],
            'serial_number' => $serial_number,
            'branch_id' => $branch_id,
            'letter' => $service_data->service_letter,
            'token_number' => $token_number,
            'customer_waiting' => $customer_waiting,
            'token_year' => date('Y'),
            'created_at' => now()
        );

        $data = Token::create($insert_data);

        if($data==true){

            return array(
                'status' =>1,
                'insert_data' =>$insert_data,
            );
        }
        else{

            return array(
                'status' =>0,
            );
        }
    }

    public static function generate_reference_number($branch_id, $floor_id, $service_id){

        $year = date('Y');
        DB::enableQueryLog();

        $last_record = Token::where('branch_id', $branch_id)
            ->select('serial')
            ->where('floor_id', $floor_id)
            ->where('service_id', $service_id)
            ->where('token_year', $year)
            ->orderBy('id', 'desc')
            ->first();

        $serial = $last_record ? $last_record->serial + 1 : 1;

        $formatted_erial = str_pad($serial, 7, '0', STR_PAD_LEFT);

        $reference_no = $branch_id.'-'.$floor_id.'-'.$service_id.'-'.$year.'-'.$formatted_erial;

        return $data = array(
            'reference_no'=> $reference_no,
            'serial' => $serial,
        );
    }

    public static function generate_serial_number($branch_id, $floor_id, $service_id){

        $date = date('Y-m-d');

        $last_record = Token::where('branch_id', $branch_id)
            ->select('serial_number','position')
            ->where('floor_id', $floor_id)
            ->where('service_id', $service_id)
            ->whereDate('created_at', '=', $date)
            ->orderBy('id', 'desc')
            ->first();

        $serial_number = $last_record ? $last_record->serial_number + 1 : 1;
        $position = $last_record ? $last_record->position + 1 : 1;

        return array(
            'serial_number' => $serial_number,
            'position' => $position,
        );
    }

    public static function get_service_details($id){

        $data = DB::table('services')
                ->select('service_name','service_code','service_title','service_letter','service_start_number')
                ->where('delete_status',0)
                ->where('status',1)
                ->where('id',$id)
                ->first();

        return $data;
    }

    public static function get_customer_waiting_number($branch_id, $floor_id, $service_id){

        $date = date('Y-m-d');

        $customer_waiting_number = Token::where('branch_id', $branch_id)
            ->select('id')
            ->where('floor_id', $floor_id)
            ->where('service_id', $service_id)
            ->where('call_status', 0)
            ->whereDate('created_at', '=', $date)
            ->count();

        return $customer_waiting_number;
    }

    public static function get_service_counter($token_data){

        $data = DB::table('counters as a')
                ->select('a.id','a.counter_name')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->where('a.branch_id',$token_data[0])
                ->where('a.floor_id',$token_data[1]);

        if ($token_data[3]!='') {

            $counter_arr = explode(",",$token_data[3]);

            $data->whereIn('a.id',$counter_arr);
        }

        if ($token_data[2]!='') {

            $assign_service_arr = explode(",",$token_data[2]);

            $data->where(function ($query) use ($assign_service_arr) {

                $conditions = implode('', array_map(function ($value) {

                    if($value!=''){

                        return "OR FIND_IN_SET($value, a.assign_service) ";
                    }
                }, $assign_service_arr));

                if($conditions!=''){

                    $conditions = substr($conditions, 2);

                    $query->whereRaw($conditions);
                }
            });
        }

        return $result = $data->orderBy('a.counter_name', 'asc')->get();
    }

    public static function get_counter_wise_token_data($request){

        $return_data = array();

        try {

            $branch_id_arr = explode(",",$request->branch_id);
            $floor_id_arr = explode(",",$request->floor_id);
            $service_id_arr = explode(",",$request->service_id);
            $counter_id_arr = explode(",",$request->counter_id);

            $date = date('Y-m-d');
            
            $data = DB::table('tokens as a')
                ->select('a.id','a.token_number','a.customer_waiting','a.branch_id','a.floor_id','a.service_id','a.counter_id','a.call_status','a.reference_no','a.name','a.email','a.phone','a.vip_status','a.user_id','a.called_date','a.started_at','a.complete_at','a.waiting_time','a.counter_time','a.turn_around_time','a.served_status','a.not_show_status','a.created_at','a.token_year')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('a.branch_id',$branch_id_arr)
                ->whereIn('a.floor_id',$floor_id_arr)
                ->whereIn('a.service_id',$service_id_arr)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->where(function ($query) use ($counter_id_arr) {
                    $query->where('a.counter_id', 0)
                        ->orWhere('a.counter_id', null);
                })
                ->where(function ($query) {
                    $query->where('a.call_status', 0)
                        ->orWhere('a.call_status', null);
                })
                ->where(function ($query) use ($counter_id_arr) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) use ($counter_id_arr) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->orderBy('a.call_status','desc')
                ->orderBy('a.id','asc')
                ->limit(10)
                ->get()->toArray();

            $return_data['data'] = $data;
            $return_data['status'] = 1;
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function get_counter_wise_call_token($request){

        $return_data = array();

        try {

            $branch_id_arr = explode(",",$request->branch_id);
            $floor_id_arr = explode(",",$request->floor_id);
            $service_id_arr = explode(",",$request->service_id);
            $counter_id_arr = explode(",",$request->counter_id);

            $date = date('Y-m-d');
            
            $data = DB::table('tokens as a')
                ->select('a.id','a.token_number','a.customer_waiting','a.branch_id','a.floor_id','a.service_id','a.counter_id','a.call_status','a.reference_no','a.name','a.email','a.phone','a.vip_status','a.user_id','a.called_date','a.started_at','a.complete_at','a.waiting_time','a.counter_time','a.turn_around_time','a.served_status','a.not_show_status','a.created_at','a.token_year','a.current_call_status','a.pre_call_status')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('a.branch_id',$branch_id_arr)
                ->whereIn('a.floor_id',$floor_id_arr)
                ->whereIn('a.service_id',$service_id_arr)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->where('a.call_status', 1)
                ->WhereIn('a.counter_id', $counter_id_arr)
                ->where(function ($query) use ($counter_id_arr) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) use ($counter_id_arr) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->orderBy('a.id','asc')
                ->limit(count($counter_id_arr))
                ->get()->toArray();

            $return_data['data'] = $data;
            $return_data['status'] = 1;
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function load_token($request){

        $return_data = array();

        try {

            $branch_id =  $request->branch_id;
            $floor_id =  $request->floor_id;
            $service_id_arr = explode(",",$request->service_id);
            $counter_id =  $request->counter_id;

            $date = date('Y-m-d');

            $data = DB::table('tokens as a')
                ->select('a.id','a.token_number','a.customer_waiting','a.branch_id','a.floor_id','a.service_id','a.counter_id','a.call_status','a.reference_no','a.name','a.email','a.phone','a.vip_status','a.user_id','a.called_date','a.started_at','a.complete_at','a.waiting_time','a.counter_time','a.turn_around_time','a.served_status','a.not_show_status','a.created_at','a.token_year')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->where('a.branch_id',$branch_id)
                ->where('a.floor_id',$floor_id)
                ->whereIn('a.service_id',$service_id_arr)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->where(function ($query) {
                    $query->where('a.counter_id', 0)
                        ->orWhere('a.counter_id', null);
                })
                ->where(function ($query) {
                    $query->where('a.call_status', 0)
                        ->orWhere('a.call_status', null);
                })
                ->where(function ($query) {
                    $query->where('a.user_id', 0)
                        ->orWhere('a.user_id', null);
                })
                ->where(function ($query) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->orderBy('a.id','asc')
                ->limit(10)
                ->get()->toArray();

            $return_data['data'] = $data;
            $return_data['status'] = 1;
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function call_next($request){

        $return_data = array();

        try {

            $branch_id =  $request->branch_id;
            $floor_id =  $request->floor_id;
            $service_id_arr = explode(",",$request->service_id);
            $counter_id =  $request->counter_id;

            $date = date('Y-m-d');

            $session_data = CommonController::get_session_data();

            $user_id = $session_data['id'];

            $data = DB::table('tokens as a')
                ->select('a.*')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->where('a.branch_id',$branch_id)
                ->where('a.floor_id',$floor_id)
                ->whereIn('a.service_id',$service_id_arr)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->where('a.counter_id', $counter_id)
                ->where('a.call_status', 1)
                ->where('a.user_id', $user_id)
                ->where(function ($query) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->orderBy('a.id','asc')
                ->limit(1)
                ->first();

            if (!empty($data)) {
                
                $update_arr = array(
                    'served_status' =>0,
                    'not_show_status' =>0,
                    'token_status' => 0,
                    'current_call_status' =>DB::raw('a.current_call_status+1'),
                    'counter_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.started_at, NOW()))'),
                );

                $update_data = DB::table('tokens as a')
                    ->where('a.id',$data->id)
                    ->update($update_arr);

                $data = DB::table('tokens as a')
                    ->select('a.*')
                    ->where('a.id',$data->id)
                    ->first();

                $return_data['data'] = $data;
                $return_data['status'] = 1;

                Common::add_user_activity_history('tokens',$data->id,'Recall Token Data','',14,0);
            }
            else{

                $update_arr = array(
                    'call_status' =>1,
                    'user_id' =>$user_id,
                    'called_date' => $date,
                    'started_at' => now(),
                    'waiting_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.created_at, NOW()))'),
                    'served_status' =>0,
                    'not_show_status' =>0,
                    'current_call_status' =>1,
                    'pre_call_status'=>0,
                    'token_status' =>0,
                    'counter_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, NOW(), NOW()))'),
                    'counter_id' => $counter_id
                );

                $update_data = DB::table('tokens as a')
                    ->where('a.delete_status',0)
                    ->where('a.status',1)
                    ->where('a.branch_id',$branch_id)
                    ->where('a.floor_id',$floor_id)
                    ->whereIn('a.service_id',$service_id_arr)
                    ->whereDate('a.created_at', '>=', $date)
                    ->whereDate('a.created_at', '<=', $date)
                    ->where(function ($query) {
                        $query->where('a.counter_id', 0)
                            ->orWhere('a.counter_id', null);
                    })
                    ->where(function ($query) {
                        $query->where('a.call_status', 0)
                            ->orWhere('a.call_status', null);
                    })
                    ->where(function ($query) {
                        $query->where('a.user_id', 0)
                            ->orWhere('a.user_id', null);
                    })
                    ->where(function ($query) {
                        $query->where('a.served_status', 0)
                            ->orWhere('a.served_status', null);
                    })
                    ->where(function ($query) {
                        $query->where('a.not_show_status', 0)
                            ->orWhere('a.not_show_status', null);
                    })
                    ->orderBy('a.id','asc')
                    ->limit(1)
                    ->update($update_arr);

                $data = DB::table('tokens as a')
                    ->select('a.*')
                    ->where('a.delete_status',0)
                    ->where('a.status',1)
                    ->where('a.branch_id',$branch_id)
                    ->where('a.floor_id',$floor_id)
                    ->whereIn('a.service_id',$service_id_arr)
                    ->whereDate('a.created_at', '>=', $date)
                    ->whereDate('a.created_at', '<=', $date)
                    ->where('a.counter_id', $counter_id)
                    ->where('a.call_status', 1)
                    ->where('a.user_id', $user_id)
                    ->where(function ($query) {
                        $query->where('a.served_status', 0)
                            ->orWhere('a.served_status', null);
                    })
                    ->where(function ($query) {
                        $query->where('a.not_show_status', 0)
                            ->orWhere('a.not_show_status', null);
                    })
                    ->orderBy('a.id','asc')
                    ->limit(1)
                    ->first();

                $return_data['data'] = $data;
                $return_data['status'] = 1;

                if (!empty($data)) {
                    
                    Common::add_user_activity_history('tokens',$data->id,'Call Token Data','',13,0);   
                }
            }
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function recall($request){

        $return_data = array();

        try {

            $token_id =  $request->token_id;
                
            $update_arr = array(
                'served_status' =>0,
                'not_show_status' =>0,
                'token_status' =>0,
                'current_call_status' =>DB::raw('a.current_call_status+1'),
                //'pre_call_status'=>DB::raw('a.pre_call_status+1'),
                'counter_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.started_at, NOW()))'),
            );

            $update_data = DB::table('tokens as a')
                ->where('a.id',$token_id)
                ->update($update_arr);

            $data = DB::table('tokens as a')
                ->select('a.*')
                ->where('a.id',$token_id)
                ->first();

            $return_data['data'] = $data;
            $return_data['status'] = 1;

            Common::add_user_activity_history('tokens',$token_id,'Recall Token Data','',14,0);
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function served($request){

        $return_data = array();

        try {

            $token_id =  $request->token_id;
                
            $update_arr = array(
                'served_status' =>1,
                'not_show_status' =>0,
                'token_status' =>1,
                'complete_at' => now(),
                'counter_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.started_at, NOW()))'),
                'turn_around_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.started_at, NOW()) + TIME_TO_SEC(a.waiting_time))'),
            );

            $update_data = DB::table('tokens as a')
                ->where('a.id',$token_id)
                ->update($update_arr);

            $return_data['status'] = 1;

            Common::add_user_activity_history('tokens',$token_id,'Served Token Data','',16,0);
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function noshow($request){

        $return_data = array();

        try {

            $token_id =  $request->token_id;
                
            $update_arr = array(
                'served_status' =>0,
                'not_show_status' =>1,
                'token_status' =>2,
                'complete_at' => now(),
                'counter_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.started_at, NOW()))'),
                'turn_around_time' => DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, a.started_at, NOW()) + TIME_TO_SEC(a.waiting_time))'),
            );

            $update_data = DB::table('tokens as a')
                ->where('a.id',$token_id)
                ->update($update_arr);

            $return_data['status'] = 1;

            Common::add_user_activity_history('tokens',$token_id,'Not Show Token Data','',15,0);
        }
        catch (Exception $e) {
            
            $return_data['status'] = 0;
        }

        return $return_data;
    }

    public static function check_counter($request){

        $return_data = array();

        $counter_id =  $request->counter_id;
        $date = date('Y-m-d');

        $session_data = CommonController::get_session_data();

        $user_id = $session_data['id'];

        $data = DB::table('counter_block_histories as a')
            ->select('a.id','a.user_id')
            ->where('a.delete_status',0)
            ->where('a.status',1)
            ->where('a.block_status',1)
            ->where('a.counter_id',$counter_id)
            ->where('a.block_date',$date)
            ->limit(1)
            ->first();

        if(empty($data)){

            $data_arr = array(
                'counter_id' => $counter_id,
                'user_id' => $user_id,
                'block_date' => $date,
                'block_status' => 1,
                'process_status' => 0,
                'actual_status' => 0,
                'add_by' => $user_id,
                'created_at' => now()
            );

            DB::table('counter_block_histories')->insert($data_arr);

            $return_data['status'] = 1;
        }
        else{

            if($data->user_id==$user_id){

                $return_data['status'] = 1;
            }
            else{

                $return_data['status'] = 0;
            }
        }

        if($return_data['status']==1){

            $update_arr = array(
                'call_status' =>0,
                'user_id' =>0,
                'served_status' =>0,
                'not_show_status' =>0,
                'token_status' =>0,
                'current_call_status' =>0,
                'pre_call_status'=>0,
                'counter_time' => null,
                'waiting_time' => null,
                'turn_around_time' => null,
                'counter_id' => 0,
            );

            $update_data = DB::table('tokens as a')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->where('a.user_id',$user_id)
                ->where('a.call_status',1)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->whereNotIn('a.counter_id', [0])
                ->whereNotNull('a.counter_id')
                ->where(function ($query) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->update($update_arr);
        }

        return $return_data;
    }

    public static function check_counter_status($request){

        $return_data = 1;

        $counter_id =  $request->counter_id;
        $date = date('Y-m-d');

        $session_data = CommonController::get_session_data();

        $user_id = $session_data['id'];

        $data = DB::table('counter_block_histories as a')
            ->select('a.id','a.user_id')
            ->where('a.delete_status',0)
            ->where('a.status',1)
            ->where('a.block_status',1)
            ->where('a.counter_id',$counter_id)
            ->where('a.block_date',$date)
            ->limit(1)
            ->first();

        if(empty($data)){

            $return_data = 2;
        }
        else{

            if($data->user_id==$user_id){

                $return_data = 1;
            }
            else{

                $return_data = 3;
            }
        }

        return $return_data;
    }

    public static function release_counter($request){

        try {

            $counter_id_arr = explode(",",$request->counter_id);
            $date = date('Y-m-d');

            $session_data = CommonController::get_session_data();

            $user_id = $session_data['id'];

            $update_arr = array(
                'block_status' =>0,
                'process_status' =>0,
                'actual_status' =>17,
                'edit_by' =>$user_id,
                'updated_at' =>now(),
            );

            $update_data = DB::table('counter_block_histories as a')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->where('a.block_date',$date)
                ->whereIn('a.counter_id', $counter_id_arr)
                ->update($update_arr);

            $update_arr = array(
                'call_status' =>0,
                'user_id' =>0,
                'served_status' =>0,
                'not_show_status' =>0,
                'token_status' =>0,
                'current_call_status' =>0,
                'pre_call_status'=>0,
                'counter_time' => null,
                'waiting_time' => null,
                'turn_around_time' => null,
                'counter_id' => 0,
            );

            $update_data = DB::table('tokens as a')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('a.counter_id',$counter_id_arr)
                ->where('a.call_status',1)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->where(function ($query) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->update($update_arr);
            
            $return_status = 1;

        } catch (Exception $e) {
            
            $return_status = 0;
        }

        return $return_status;
    }

    public static function cancel_counter($request){

        try {

            $counter_id_arr = explode(",",$request->counter_id);
            $date = date('Y-m-d');

            $session_data = CommonController::get_session_data();

            $user_id = $session_data['id'];

            $update_arr = array(
                'block_status' =>0,
                'process_status' =>0,
                'actual_status' =>18,
                'edit_by' =>$user_id,
                'updated_at' =>now(),
            );

            $update_data = DB::table('counter_block_histories as a')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->where('a.block_status',1)
                ->where('a.user_id',$user_id)
                ->where('a.block_date',$date)
                ->whereIn('a.counter_id', $counter_id_arr)
                ->update($update_arr);

            $update_arr = array(
                'call_status' =>0,
                'user_id' =>0,
                'served_status' =>0,
                'not_show_status' =>0,
                'token_status' =>0,
                'current_call_status' =>0,
                'pre_call_status'=>0,
                'counter_time' => null,
                'waiting_time' => null,
                'turn_around_time' => null,
                'counter_id' => 0,
            );

            $update_data = DB::table('tokens as a')
                ->where('a.delete_status',0)
                ->where('a.status',1)
                ->whereIn('a.counter_id',$counter_id_arr)
                ->where('a.call_status',1)
                ->where('a.user_id',$user_id)
                ->whereDate('a.created_at', '>=', $date)
                ->whereDate('a.created_at', '<=', $date)
                ->where(function ($query) {
                    $query->where('a.served_status', 0)
                        ->orWhere('a.served_status', null);
                })
                ->where(function ($query) {
                    $query->where('a.not_show_status', 0)
                        ->orWhere('a.not_show_status', null);
                })
                ->update($update_arr);
            
            $return_status = 1;

        } catch (Exception $e) {
            
            $return_status = 0;
        }

        return $return_status;
    }

    public static function my_call_list_grid($request){

        $session_data = CommonController::get_session_data();

        $user_id = $session_data['id'];

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

            $total_records = Token::leftJoin('branches', 'branches.id', '=', 'tokens.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'tokens.floor_id')
                ->leftJoin('services', 'services.id', '=', 'tokens.service_id')
                ->leftJoin('counters', 'counters.id', '=', 'tokens.counter_id')
                ->where('tokens.delete_status', 0)
                ->where('tokens.user_id', $user_id)
                ->where(function ($query) use ($search_value) {
                    $query->where('floors.floor_name','like',"%".$search_value."%")
                        ->orWhere('services.service_name','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('counters.counter_name','like',"%".$search_value."%")
                        ->orWhere('tokens.token_number','like',"%".$search_value."%")
                        ->orWhere('tokens.reference_no','like',"%".$search_value."%");
                })
                ->count();

            $data = Token::select('tokens.*','branches.branch_name', 'floors.floor_name', 'services.service_name','counters.counter_name')
                ->leftJoin('branches', 'branches.id', '=', 'tokens.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'tokens.floor_id')
                ->leftJoin('services', 'services.id', '=', 'tokens.service_id')
                ->leftJoin('counters', 'counters.id', '=', 'tokens.counter_id')
                ->where('tokens.delete_status',0)
                ->where('tokens.user_id', $user_id)
                ->where(function ($query) use ($search_value) {
                    $query->where('floors.floor_name','like',"%".$search_value."%")
                        ->orWhere('services.service_name','like',"%".$search_value."%")
                        ->orWhere('branches.branch_name','like',"%".$search_value."%")
                        ->orWhere('counters.counter_name','like',"%".$search_value."%")
                        ->orWhere('tokens.token_number','like',"%".$search_value."%")
                        ->orWhere('tokens.reference_no','like',"%".$search_value."%");
                })
                ->orderBy($column_name,$column_ort_order)
                ->offset($row)
                ->limit($row_per_page)
                ->get();
        }
        else{

            $total_records = Token::leftJoin('branches', 'branches.id', '=', 'tokens.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'tokens.floor_id')
                ->leftJoin('services', 'services.id', '=', 'tokens.service_id')
                ->leftJoin('counters', 'counters.id', '=', 'tokens.counter_id')
                ->where('tokens.delete_status', 0)
                ->where('tokens.user_id', $user_id)
                ->count();

            $data = Token::select('tokens.*','branches.branch_name', 'floors.floor_name', 'services.service_name','counters.counter_name')
                ->leftJoin('branches', 'branches.id', '=', 'tokens.branch_id')
                ->leftJoin('floors', 'floors.id', '=', 'tokens.floor_id')
                ->leftJoin('services', 'services.id', '=', 'tokens.service_id')
                ->leftJoin('counters', 'counters.id', '=', 'tokens.counter_id')
                ->where('tokens.delete_status',0)
                ->where('tokens.user_id', $user_id)
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

    public static function my_call_list_chart(){

        $session_data = CommonController::get_session_data();

        $user_id = $session_data['id'];

        $date = date('Y-m-d');
        $fast_date = date('Y-m-01', strtotime($date));
        $last_date = date('Y-m-t', strtotime($date));

        $result = Token::select(DB::raw('count(id) as total'), 'token_status', DB::raw('DAY(called_date) as day'))
            ->where('delete_status', 0)
            ->where('user_id', $user_id)
            ->whereDate('created_at', '>=', $fast_date)
            ->whereDate('created_at', '<=', $last_date)
            ->groupBy('token_status', DB::raw('DAY(called_date)'))
            ->get();

        $data = array();

        foreach($result as $row){

            $data[$row->token_status][$row->day] = $row->total;
        }

        return $data;
    }
}