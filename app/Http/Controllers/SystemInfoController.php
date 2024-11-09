<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\SystemSetting;

class SystemInfoController extends Controller
{

    public function __construct(){

    }

    public function system_config_add_page(){
        
        $system_data = Common::get_system_setting_data();

        return view('admin.system_config.system_info',compact('system_data'));
    }

    public function system_config_strore(Request $request){
        
        $validator = Validator::make($request->all(), [
            'system_name' => 'required|string|max:250',
            'system_title' => 'required|string|max:250',
            'system_slogan' => 'required|string|max:250',
            'system_email' => 'required|email|max:100',
            'system_mobile' => 'required|string|max:20',
            'system_address' => 'required|string|max:250',
            'system_version' => 'required|string|max:100',
            'system_copy_right' => 'required|string|max:250',
            'system_details' => 'required|string|max:250',
            'system_logo' => $request->input('hidden_system_logo') === '' ? 'required' : '',
            'system_icon' => $request->input('hidden_system_icon') === '' ? 'required' : '',
            'system_bg_image' => $request->input('hidden_system_bg_image') === '' ? 'required' : ''
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $status = SystemSetting::update_system_info($request);

        $notification = array();

        if($status==1){

            $notification = array(
                'message'=> "System Details Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "System Details Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function user_rules_add_page(){
        
        $system_data = Common::get_system_setting_data();

        return view('admin.system_config.user_rules',compact('system_data'));
    }

    public function user_rules_strore(Request $request){
        
        $validator = Validator::make($request->all(), [
            'password_validity_days' => 'required|integer',
            'password_min_length' => 'required|integer',
            'password_max_length' => 'required|integer',
            'default_password_type' => 'required|integer',
            'userid_min_length' => 'required|integer',
            'userid_max_length' => 'required|integer',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $status = SystemSetting::update_user_rules($request);

        $notification = array();

        if($status==1){

            $notification = array(
                'message'=> "User Id & Password Rules Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Id & Password Rules Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function session_time_page(){
        
        $system_data = Common::get_system_setting_data();

        return view('admin.system_config.session_time',compact('system_data'));
    }

    public function session_time_strore(Request $request){
        
        $validator = Validator::make($request->all(), [
            'session_time' => 'required|integer',
            'session_check_time' => 'required|integer',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $status = SystemSetting::update_session_time($request);

        $notification = array();

        if($status==1){

            $notification = array(
                'message'=> "Session Time Updated Successfully",
                'alert_type'=>'success',
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Session Time Does Not Updated Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }
}