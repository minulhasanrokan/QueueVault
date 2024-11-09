<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Common;
use Illuminate\Support\Facades\Hash;

class CommonController extends Controller
{

    public function __construct(){

    }

    public static function get_session_variable_name(){

        return env('SESSION_NAME','queue_management');
    }

    public static function get_session_data(){

        $session_name = CommonController::get_session_variable_name();

        $session_data = session()->all();

        if(isset($session_data[$session_name])){

            return $session_data[$session_name];
        }

        return array();
    }

    public static function forgot_session_data(){

        session()->forget(CommonController::get_session_variable_name());
    }


    public function check_session(){

        $session_data = CommonController::get_session_data();

        if(!empty($session_data)){

            $last_active_time = $session_data['last_active_time'];
            $user_session_time = $session_data['user_session_time'];
            $lock_status = $session_data['lock_status'];

            if($lock_status==1){

                echo 'lock';
            }
            else{

                if((time() - $last_active_time) > $user_session_time){

                    Common::update_user_log_time(1,1);

                    CommonController::forgot_session_data();

                    echo 'Session Expire';
                }
                else{

                    $total_log_data= Common::get_user_log_histories_data();

                    if ($total_log_data>0) {
                        
                        Common::update_user_log_time(0,0);

                        echo csrf_token();
                    }
                    else{

                        CommonController::forgot_session_data();

                        echo 'Session Expire';
                    }
                }
            }
        }
        else{

            CommonController::forgot_session_data();

            echo 'Session Expire';
        }
    }

    public function update_session(){

        $session_data = CommonController::get_session_data();

        if(!empty($session_data)){

            $last_active_time = $session_data['last_active_time'];
            $user_session_time = $session_data['user_session_time'];
            $lock_status = $session_data['lock_status'];

            if($lock_status==1){

                echo 'lock';
            }
            else{

                if((time() - $last_active_time) > $user_session_time){

                    Common::update_user_log_time(1,1);

                    CommonController::forgot_session_data();

                    echo 'Session Expire';
                }
                else{

                    $total_log_data= Common::get_user_log_histories_data();

                    if ($total_log_data>0) {
                        
                        Common::update_user_log_time(0,0);

                        $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';

                        session()->put($last_active_time_Key, time());

                        echo csrf_token();
                    }
                    else{

                        CommonController::forgot_session_data();

                        echo 'Session Expire';
                    }
                }
            }
        }
        else{

            CommonController::forgot_session_data();

            echo 'Session Expire';
        }
    }

    public static function upsate_last_active_time(){

        session()->put(CommonController::get_session_variable_name().'.last_active_time', time());
    }

    public static function get_duplicate_value(Request $request){

        $status = Common::get_duplicate_value($request->field_name,$request->table_name, $request->value, $request->data_id);

        $data = array(
            'status'=>$status,
            'csrf_token' => csrf_token()
        );

        return response()->json($data);
    }

    public static function get_combain_duplicate_value(Request $request){

        $status = Common::get_combain_duplicate_value($request->table_name, $request->field_values, $request->data_id);

        $data = array(
            'status'=>$status,
            'csrf_token' => csrf_token()
        );

        return response()->json($data);
    }

    public static function encrypt_data($data){

        $encrypt_method = env('APP_ENCRIPTION_METHOD','AES-256-CBC');
        $secret_key = env('APP_ENCRIPTION_SECRET_KEY','adminadminadmina');
        $secret_iv = env('APP_ENCRIPTION_SECRET_IV','adminadminadmina');

        $key = hash(env('APP_ENCRIPTION_HASH','sha256'), $secret_key);
        $iv = substr(hash(env('APP_ENCRIPTION_HASH','sha256'), $secret_iv), 0, 16);

        $encryptToken = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
        $encryptToken = base64_encode($encryptToken);

        return urlencode($encryptToken);
    }

    public static function decrypt_data($data){

        $data = urldecode($data);

        $encrypt_method = env('APP_ENCRIPTION_METHOD','AES-256-CBC');
        $secret_key = env('APP_ENCRIPTION_SECRET_KEY','adminadminadmina');
        $secret_iv = env('APP_ENCRIPTION_SECRET_IV','adminadminadmina');

        $key = hash(env('APP_ENCRIPTION_HASH','sha256'), $secret_key);
        $iv = substr(hash(env('APP_ENCRIPTION_HASH','sha256'), $secret_iv), 0, 16);

        return openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);
    }

    public static function return_message_history($id=null,$table_name=null){

        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();

        $message_data = Common::return_message_history_data($id,$table_name);

        return view('admin.return_message_history',compact('message_data','actual_status','process_status'));
    }

    public static function activity_history($id=null,$table_name=null){

        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();
        
        $history_data = Common::activity_history_data($id,$table_name);

        return view('admin.activity_history',compact('history_data','actual_status','process_status'));
    }

    public static function dashboard_right(){

        return Common::dashboard_right();
    }

    public static function get_dropdown($table_name,$field_name,$value,$other_field,$other_value,$not_equal_field,$not_equal_value){

        return Common::get_dropdown($table_name,$field_name,$value,$other_field,$other_value,$not_equal_field,$not_equal_value);
    }

    public static function get_dynamic_password($password_type,$min_length,$max_length){

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);
        $length = rand($min_length, $max_length);

        $pass_string = '';

        for ($i = 0; $i < $length; $i++) {
            $pass_string .= $characters[rand(0, $charactersLength - 1)];
        }

        return $pass_string;
    }

    public static function hash_password($pass_string){

        return $hashedPassword = Hash::make($pass_string);
    }
}
