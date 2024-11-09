<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Common;
use App\Models\User;

class DashboardController extends Controller
{
    
    public function __construct(){

    }

    public function dashboard(){

        Common::delete_cahce_data('*');

        $session_data = CommonController::get_session_data();

        $super_admin_status = $session_data['super_admin_status'];

        $system_data = Common::get_system_setting_data();

        $dashboard_right = CommonController::dashboard_right();

        $data_status = Common::get_data_status_data();
        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();

        return view('admin.dashboard',compact('system_data','dashboard_right','super_admin_status','data_status','actual_status','process_status'));
    }

    public function logout(){

        Common::update_user_log_time(1,0);

        CommonController::forgot_session_data();

        return redirect('/login');
    }

    public function lock(){

        $system_data = Common::get_system_setting_data();

        return view('admin.lock',compact('system_data')); 
    }

    public function un_lock(Request $request){

        $request->validate(
            [
                'password' => 'required'
            ]
        );

        $session_data = CommonController::get_session_data();

        $user_data = User::get_user_by_email($session_data['email']);

        $pass_check_status = Hash::check(trim($request->password), $user_data->password);

        if($pass_check_status!=1){

            $notification = array(
                'message'=> "Password Does Not Matched!",
                'alert_type'=>'warning'
            );

            return redirect()->back()->with($notification);
        }
        else{

            $notification = array(
                'message'=> "User Unlocked Successfully!",
                'alert_type'=>'success'
            );

            $lock_status = CommonController::get_session_variable_name().'.lock_status';
            session()->put($lock_status, 0);

            $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';
            session()->put($last_active_time_Key, time());

            return redirect('/dashboard')->with($notification);
        }
    }
}