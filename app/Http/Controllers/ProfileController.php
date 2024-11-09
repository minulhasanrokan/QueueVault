<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\User;
use App\Http\Controllers\CommonController;

class ProfileController extends Controller
{
    public function __construct(){

    }

    public function change_password_page(){

        $menu_data = Common::get_page_menu();
        $system_data = Common::get_system_setting_data();

        return view('admin.profile.change_password',compact('menu_data','system_data'));
    }

    public function change_password(Request $request){

        $system_data = Common::get_system_setting_data();

        $request->validate(
            [
                'old_password'    => 'required|string',
                'password' => 'required|alpha_num|min:'.$system_data["password_min_length"].'|max:'.$system_data["password_max_length"],
                'c_password' => 'required|same:password',
            ]
        );

        $session_data = CommonController::get_session_data();

        $user_data = User::get_user_by_email(trim($session_data['email']));

        $pass_check_status = Hash::check(trim($request->old_password), $user_data->password);

        if($pass_check_status!=1){

            $notification = array(
                'message'=> "Old Password Does Not Matched!",
                'alert_type'=>'warning',
                'id'=>1,
                'csrf_token' => csrf_token()
            );

            CommonController::upsate_last_active_time();

            return response()->json($notification);
        }
        else{

            $pre_pass_check_status = Common::check_previous_four_password($user_data->id,$request->password);

            if($pre_pass_check_status==0){

                $notification = array(
                    'message'=> "You Can Not Use Immediate Four Password!",
                    'alert_type'=>'warning',
                    'id'=>1,
                    'csrf_token' => csrf_token()
                );

                CommonController::upsate_last_active_time();

                return response()->json($notification);
            }

            $password = Hash::make($request->password);

            $status = User::update_user_password($user_data->id,$password);

            if($status==1)
            {
                Common::insert_password_history($user_data->id,$password);

                Common::add_user_activity_history('users',$user_data->id,'Change Password','',7,0);

                $notification = array(
                    'message'=> "User Password Changed Successfully",
                    'alert_type'=>'success',
                    'id'=>$status,
                    'csrf_token' => csrf_token()
                );

                $password_change_status = CommonController::get_session_variable_name().'.password_change_status';
                session()->put($password_change_status, 1);

                $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';
                session()->put($last_active_time_Key, time());

                return response()->json($notification);
            }
            else{

                $notification = array(
                    'message'=> "User Password Does Not Changed Successfully",
                    'alert_type'=>'warning',
                    'id'=>$status,
                    'csrf_token' => csrf_token()
                );

                CommonController::upsate_last_active_time();

                return response()->json($notification);
            }
        }
    }

    public function profile_details_page(){

        $menu_data = Common::get_page_menu();
        $session_data = CommonController::get_session_data();

        $single_data = User::get_single_data_by_id($session_data['id']);

        return view('admin.profile.profile_details',compact('menu_data','single_data'));
    }
}