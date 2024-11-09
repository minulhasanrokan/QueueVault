<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\FailedLoginAttempt;
use App\Models\Common;
use App\Models\User;
use DB;

class LoginController extends Controller
{

    public function __construct(){

    }
    
    public function login_page(){

        $system_data = Common::get_system_setting_data();

        return view('admin.login_page',compact('system_data'));
    }

    public function login_store(Request $request){

        CommonController::forgot_session_data();

        $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required'
            ]
        );

        $user_data = User::get_user_by_email(trim($request->email));

        if(empty($user_data)){

            $notification = array(
                'message'=> "User E-mail Or Password Does Not Matched!",
                'alert_type'=>'warning'
            );

            return redirect()->back()->with($notification);
        }
        else {
            
            $pass_check_status = Hash::check(trim($request->password), $user_data->password);

            if($pass_check_status!=1){

                $ipAddress = $request->ip();
                $userAgent = $request->header('User-Agent');

                $failedAttempt = FailedLoginAttempt::firstOrNew([
                    'email' => $request->email,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                ]);

                $failedAttempt->attempts += 1;

                if ($failedAttempt->attempts >= 3) {

                    $failedAttempt->save();

                    $user = User::where('email', $request->email)->first();

                    if ($user) {
                        $user->block_status = 1;
                        $user->save();

                        FailedLoginAttempt::where('email', $request->email)
                            //->where('ip_address', $request->ip())
                            //->where('user_agent', $request->header('User-Agent'))
                            ->delete();

                        $notification = array(
                            'message'=> "Your Account Was Locked, Please Contact With Adminstrator",
                            'alert_type'=>'warning'
                        );

                        return redirect()->back()->with($notification);
                    }
                }

                $notification = array(
                    'message'=> "User E-mail Or Password Does Not Matched!",
                    'alert_type'=>'warning'
                );

                return redirect()->back()->with($notification);
            }
            else{

                if($user_data->status==0){

                    $notification = array(
                        'message'=> "Your Account Was Deactivated, Please Contact With Adminstrator",
                        'alert_type'=>'warning'
                    );

                    return redirect()->back()->with($notification);
                }
                if($user_data->block_status==1){

                    $notification = array(
                        'message'=> "Your Account Was Locked, Please Contact With Adminstrator",
                        'alert_type'=>'warning'
                    );

                    return redirect()->back()->with($notification);
                }
                else if($user_data->verify_status==0){

                    $notification = array(
                        'message'=> "Please Check Your E-mail and Verify Your Account First",
                        'alert_type'=>'warning'
                    );

                    return redirect()->back()->with($notification);
                }
                else{

                    $user_log_id = Common::insert_user_log($user_data->id);

                    if($user_log_id!=0 and is_int($user_log_id)){

                        FailedLoginAttempt::where('email', $request->email)
                            //->where('ip_address', $request->ip())
                            //->where('user_agent', $request->header('User-Agent'))
                            ->delete();

                        $user_session_data = json_decode(json_encode($user_data), true);
                        $user_session_data['last_active_time'] = time();
                        $user_session_data['user_log_id'] = $user_log_id;

                        $request->session()->put(CommonController::get_session_variable_name(), $user_session_data);

                        $notification = array(
                            'message'=> "User Account Login Successfully!",
                            'alert_type'=>'success'
                        );

                        return redirect('/dashboard')->with($notification);
                    }
                    else{

                        $notification = array(
                            'message'=> "Something Went Wrong!",
                            'alert_type'=>'warning'
                        );

                        return redirect()->back()->with($notification);
                    }
                }
            }
        }
    }

    public function change_password_page(){

        $system_data = Common::get_system_setting_data();

        return view('admin.change_pass',compact('system_data'));
    }

    public function change_password_store(Request $request){

        $system_data = Common::get_system_setting_data();

        $session_data = CommonController::get_session_data();
        
        CommonController::upsate_last_active_time();

        $request->validate(
            [
                'old_password'    => 'required',
                'new_password' => 'required|alpha_num|min:'.$system_data["password_min_length"].'|max:'.$system_data["password_max_length"],
                'c_password' => 'required|same:new_password',
            ]
        );

        $user_data = User::get_user_by_email($session_data['email']);

        $pass_check_status = Hash::check(trim($request->old_password), $user_data->password);

        if($pass_check_status!=1){

            CommonController::upsate_last_active_time();

            $notification = array(
                'message'=> "Old Password Does Not Matched!",
                'alert_type'=>'warning'
            );

            return redirect()->back()->with($notification);
        }
        else{

            $pre_pass_check_status = Common::check_previous_four_password($user_data->id,$request->new_password);

            if($pre_pass_check_status==0){

                CommonController::upsate_last_active_time();

                $notification = array(
                    'message'=> "You Can Not Use Immediate Four Password!",
                    'alert_type'=>'warning'
                );

                return redirect()->back()->with($notification);
            }

            $password = Hash::make($request->new_password);

            $status = User::update_user_password($user_data->id,$password);

            if($status==1)
            {
                Common::insert_password_history($user_data->id,$password);

                Common::add_user_activity_history('users',$user_data->id,'Change Password','',7,0);
                
                $notification = array(
                    'message'=> "User Password Changed Successfully!",
                    'alert_type'=>'success'
                );

                $password_change_status = CommonController::get_session_variable_name().'.password_change_status';
                session()->put($password_change_status, 1);

                $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';
                session()->put($last_active_time_Key, time());

                return redirect('/dashboard')->with($notification);
            }
            else{

                $notification = array(
                    'message'=> "Something Went Wrong!",
                    'alert_type'=>'warning'
                );

                CommonController::upsate_last_active_time();

                return redirect()->back()->with($notification);
            }
        }
    }

    public function forgot_password_page(){

        CommonController::forgot_session_data();

        $system_data = Common::get_system_setting_data();

        return view('admin.forgot_pass',compact('system_data'));
    }

    public function forgot_password_store(Request $request){

        CommonController::forgot_session_data();

        $notification = array();

        $request->validate(
            [
                'email' => 'required|email'
            ]
        );

        $user_data = User::get_user_by_email($request->email);

        if(empty($user_data)){

            $notification = array(
                'message'=> "No User Account Found",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            $token = rand(1000,9999);

            $user_id = CommonController::encrypt_data($user_data->id."****".$token);

            if($user_data->status==0){

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator To Active Your Account.",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if($user_data->block_status==1){

                $notification = array(
                    'message'=> "Your Account Was Locked. Please Contact With Adminstrator To Unlock Your Account.",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if($user_data->verify_status!=1){

                $notification = array(
                    'message'=> "Please Verify Your Account First",
                    'alert_type'=>'warning',
                    'create_status'=>1,
                    'verify_mail'=>1,
                    'user_id' =>$user_id,
                );
            }
            else{

                $status = MailController::sent_password_reset_email($request->email,$user_id,$user_data->name,$token,$user_data->id);
                
                if($status==false){

                    $notification = array(
                        'message'=> "Something Went Wrong",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>'',
                    );
                }
                else{

                    $updateData = [
                        'remember_token' => $token,
                    ];

                    DB::table('users')
                        ->where('id', $user_data->id)
                        ->update($updateData);

                    $email = CommonController::encrypt_data($request->email);

                    $notification = array(
                        'message'=> "A Password Reset Link Sent To Your E-mail Account",
                        'alert_type'=>'success',
                        'create_status'=>1,
                        'user_id' =>$email,
                    );
                }
            }
        }

        return redirect()->back()->with($notification);
    }

    public function reset_password_page($token){

        CommonController::forgot_session_data();

        $system_data = Common::get_system_setting_data();

        $user_data = explode("****",CommonController::decrypt_data($token));

        if($user_data[0]=='' || !isset($user_data[1])){
            
            $notification = array(
                'message'=> "Invalid Url",
                'alert_type'=>'warning',
                'create_status'=>1,
                'user_id' =>'',
            );
        }
        else{

            $data = User::where('delete_status',0)
                ->where('id',$user_data[0])
                ->where('remember_token',$user_data[1])
                ->first();

            $notification = array();

            if(empty($data) || $data->status==0){

                $notification = array(
                    'message'=> "Invalid Url",
                    'alert_type'=>'warning',
                    'create_status'=>1,
                    'user_id' =>'',
                );
            }
            else{

                $notification = array(
                    'message'=> "Reset Your Password",
                    'alert_type'=>'success',
                    'create_status'=>0,
                    'user_id' =>$token,
                );
            }
        }   

        return view('admin.reset_passwoer_page',compact('system_data','notification'));
    }

    public function reset_password_store($token, Request $request){

        CommonController::forgot_session_data();

        $system_data = Common::get_system_setting_data();

        $request->validate(
            [
                'token'              =>      'required|string|max:4',
                'password'          =>      'required|alpha_num|min:'.$system_data["password_min_length"].'|max:'.$system_data["password_max_length"],
                'confirm_password'  =>      'required|same:password'
            ]
        );

        $user_data = explode("****",CommonController::decrypt_data($token));

        $request_token = explode("****",CommonController::decrypt_data($request->hidden_token));

        $diff_token = array_diff($user_data, $request_token);

        $notification = array();

        if(!empty($diff_token)){

            $notification = array(
                'message'=> "Something Went Wrong!",
                'alert_type'=>'warning',
                'create_status'=>1,
                'user_id' =>'',
            );
        }
        else{

            $data = User::where('delete_status',0)
                ->where('status',1)
                ->where('id',$user_data[0])
                ->where('remember_token',$user_data[1])
                ->first();

            if(empty($data)){

                $notification = array(
                    'message'=> "Something Went Wrong!",
                    'alert_type'=>'warning',
                    'create_status'=>1,
                    'user_id' =>'',
                );
            }
            else if($data->remember_token!=$request->token || $request->token!=$user_data[1]){

                $notification = array(
                    'message'=> "Toekn Not Matched!",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>$request->hidden_token,
                );
            }
            else{

                $pre_pass_check_status = Common::check_previous_four_password($data->id,$request->password);

                if($pre_pass_check_status==0){

                    $notification = array(
                        'message'=> "You Can Not Use Immediate Four Password!",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>$request->hidden_token,
                    );
                }
                else{

                    $user_id = $data->id;
                    $data->remember_token = rand(1111,9999);

                    Common::db_transaction_on();

                    $data->save();

                    $password = Hash::make($request->password);

                    $status = User::update_user_password($user_id,$password);

                    Common::insert_password_history($data->id,$password);

                    if($data==true){

                        Common::db_transaction_commit();

                        CommonController::forgot_session_data();

                        $notification = array(
                            'message'=> "Successfully Changed Your Password!",
                            'alert_type'=>'success',
                            'create_status'=>2,
                            'user_id' =>'',
                        );
                    }
                    else{

                        Common::db_transaction_rollBack();

                        $notification = array(
                            'message'=> "Something Went Wrong!",
                            'alert_type'=>'warning',
                            'create_status'=>1,
                            'user_id' =>'',
                        );
                    }
                }
            }
        }

        return view('admin.reset_passwoer_page',compact('system_data','notification'));
    }

    public function resend_forgot_password($email){

        CommonController::forgot_session_data();

        $email = CommonController::decrypt_data($email);

        $user_data = User::get_user_by_email(trim($email));

        if(empty($user_data)){

            $notification = array(
                'message'=> "No User Account Found",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            $token = rand(1000,9999);

            $user_id = CommonController::encrypt_data($user_data->id."****".$token);

            if($user_data->status==0){

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator To Active Your Account.",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if($user_data->block_status==1){

                $notification = array(
                    'message'=> "Your Account Was Locked. Please Contact With Adminstrator To Active Your Account.",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if($user_data->verify_status!=1){

                $notification = array(
                    'message'=> "Please Verify Your Account First",
                    'alert_type'=>'warning',
                    'create_status'=>1,
                    'verify_mail' =>1,
                    'user_id' =>$user_id,
                );
            }
            else{

                $status = MailController::sent_password_reset_email($email,$user_id,$user_data->name,$token,$user_data->id);

                if($status==false){

                    $notification = array(
                        'message'=> "Something Went Wrong",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>'',
                    );
                }
                else{

                    $updateData = [
                        'remember_token' => $token,
                    ];

                    DB::table('users')
                        ->where('id', $user_data->id)
                        ->update($updateData);

                    $email = CommonController::encrypt_data($email);

                    $notification = array(
                        'message'=> "A Password Reset Link Sent To Your E-mail Account",
                        'alert_type'=>'success',
                        'create_status'=>1,
                        'user_id' =>$email,
                    );
                }
            }
        }

        return redirect()->back()->with($notification);
    }

    public function resend_verify_email($token){

        CommonController::forgot_session_data();

        $notification = array();

        $user_id = CommonController::decrypt_data($token);

        $data = User::where('delete_status',0)
            ->where('id',$user_id)
            ->first();

        if($data->status==0 && !empty($data)){

            $notification = array(
                'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator To Active Your Account.",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        if($data->block_status==1 && !empty($data)){

            $notification = array(
                'message'=> "Your Account Was Locked. Please Contact With Adminstrator To Unlock Your Account.",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else if($data->verify_status==1 || empty($data)){

            $notification = array(
                'message'=> "Invalid Url",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            $status = MailController::sent_verify_email($data->email,$token,$data->name,$data->id);

            if($status==true){

                $notification = array(
                    'message'=> "A Verification Link Sent To Your E-mail Account",
                    'alert_type'=>'success',
                    'create_status'=>1,
                    'verify_mail'=>1,
                    'user_id' =>$token,
                );
            }
            else{

                $notification = array(
                    'message'=> "Something Went Wrong",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'verify_mail' =>0,
                    'user_id' =>'',
                );
            }
        }

        return redirect()->back()->with($notification);
    }

    public function verify_email_page($token){

        CommonController::forgot_session_data();

        $notification = array();

        $user_id = CommonController::decrypt_data($token);

        $data = User::where('delete_status',0)
            ->where('id',$user_id)
            ->first();

        if(empty($data)){

            $notification = array(
                'message'=> "Invalid Url",
                'alert_type'=>'warning',
                'create_status'=>0,
                'user_id' =>'',
            );
        }
        else{

            if ($data->status==0) {

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if ($data->block_status==1) {

                $notification = array(
                    'message'=> "Your Account Was Deactivated. Please Contact With Adminstrator",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else if ($data->verify_status==1) {
                
                $notification = array(
                    'message'=> "Invalid Url",
                    'alert_type'=>'warning',
                    'create_status'=>0,
                    'user_id' =>'',
                );
            }
            else{

                $data->verify_status =1;
                $data->email_verified_at =now();

                Common::db_transaction_on();

                $data->save();

                if($data==true){

                    Common::db_transaction_commit();

                    $notification = array(
                        'message'=> "Your Account Successfully Verified. Please Login To Access your Account",
                        'alert_type'=>'success',
                        'create_status'=>1,
                        'user_id' =>'',
                    );
                }
                else{

                    Common::db_transaction_rollBack();

                    $notification = array(
                        'message'=> "Something Went Wrong Please Try Again",
                        'alert_type'=>'warning',
                        'create_status'=>0,
                        'user_id' =>'',
                    );
                }
            }
        }

        $system_data = Common::get_system_setting_data();

        return view('admin.user_verify',compact('notification','system_data'));
    }
}