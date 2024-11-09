<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\Token;
use App\Http\Controllers\CommonController;

class TokenController extends Controller
{
    public function __construct(){

    }

    public function registration_url_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.url.registration_url',compact('menu_data'));
    }

    public function registration_url_create(Request $request){

        $url = url('/').'/registration/'.CommonController::encrypt_data($request->branch_id.'****'.$request->floor_id.'****'.$request->service_id);

        $status = Common::add_user_activity_history('',0,'Create Registration Url','',11,0);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Registration Url Created Successfully",
                'alert_type'=>'success',
                'url'=>$url,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Registration Url Does Not Created Successfully",
                'alert_type'=>'warning',
                'url'=>$url,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function registration($token=null){

        $token = CommonController::decrypt_data($token);

        if($token==''){

            echo '404';

            die;
        }

        $token_data = explode('****',$token);

        $service_data = Token::get_service_for_registration($token_data);

        $system_data = Common::get_system_setting_data();

        return view('frontend.registration',compact('token_data','service_data','system_data'));
    }

    public function generate_token(Request $request){

        //session()->regenerateToken();

        $token_data = Token::generate_token($request);

        $notification = array();

        if($token_data['status']==1){

            $token_data['insert_data']['date'] = date('d-m-Y');

            $notification = array(
                'message'=> "Token Generated Successfully",
                'alert_type'=>'success',
                'status'=>1,
                'data'=>$token_data['insert_data'],
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Token Does Not Generated Successfully",
                'alert_type'=>'warning',
                'status' => 0,
                'csrf_token' => csrf_token()
            );
        }

        return response()->json($notification);
    }

    public function counter_wise_display_url_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.url.counter_wise_display_url',compact('menu_data'));
    }

    public function counter_wise_display_url_create(Request $request){

        $url = url('/').'/counter-wise-display/'.CommonController::encrypt_data($request->branch_id.'****'.$request->floor_id.'****'.$request->service_id.'****'.$request->counter_id);

        $status = Common::add_user_activity_history('',0,'Create Counter Wise Display Url','',12,0);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Display Url Created Successfully",
                'alert_type'=>'success',
                'url'=>$url,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Display Url Does Not Created Successfully",
                'alert_type'=>'warning',
                'url'=>$url,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function counter_wise_display($token=null){

        $token = CommonController::decrypt_data($token);

        if($token==''){

            echo '404';

            die;
        }

        $token_data = explode('****',$token);

        $counter_data = Token::get_service_counter($token_data);

        //$service_data = Token::get_service_for_registration($token_data);

        $system_data = Common::get_system_setting_data();

        return view('frontend.counter_wise_display',compact('token_data','counter_data','system_data'));
    }

    public function get_counter_wise_token_data(Request $request){

        if($request->type=='token_list'){

            $token_data = Token::get_counter_wise_token_data($request);

            if($token_data['status']!=0){

                $notification = array(
                    'message'=> "Token Data Get Successfully",
                    'alert_type'=>'success',
                    'data'=>$token_data['data'],
                    'csrf_token' => csrf_token()
                );
            }
            else{

                $notification = array(
                    'message'=> "Token Data Does Not Get Successfully",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'csrf_token' => csrf_token()
                );
            }
        }
        else if($request->type=='call_token'){

            $token_data = Token::get_counter_wise_call_token($request);

            if($token_data['status']!=0){

                $notification = array(
                    'message'=> "Token Data Get Successfully",
                    'alert_type'=>'success',
                    'data'=>$token_data['data'],
                    'csrf_token' => csrf_token()
                );
            }
            else{

                $notification = array(
                    'message'=> "Token Data Does Not Get Successfully",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'csrf_token' => csrf_token()
                );
            }
        }

        return response()->json($notification);
    }

    public function call_page(){

        $menu_data = Common::get_page_menu();

        $session_data = CommonController::get_session_data();

        $branch_id = $session_data['branch_id'];

        return view('admin.call.call',compact('menu_data','branch_id'));
    }

    public function call_token(Request $request){

        if($request->type=='load'){

            $check_counter = Token::check_counter($request);

            if($check_counter['status']==0){

                $notification = array(
                    'message'=> "This Counter Alredy Selected By Another User",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                $call_next = Token::call_next($request);

                $token_data = Token::load_token($request);

                if($token_data['status']!=0){

                    $notification = array(
                        'message'=> "Token Data Loaded Successfully",
                        'alert_type'=>'success',
                        'data'=>$token_data['data'],
                        'call_next' =>$call_next,
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    $notification = array(
                        'message'=> "Token Data Does Not Loaded Successfully",
                        'alert_type'=>'warning',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
            }

            return response()->json($notification);
        }
        else if($request->type=='callnext'){

            $counter_status = Token::check_counter_status($request);

            if($counter_status==2){

                $notification = array(
                    'message'=> "Counter Was Released. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==3){

                $notification = array(
                    'message'=> "Counter Is Used By Another User. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==1){

                $call_next = Token::call_next($request);

                $token_data = Token::load_token($request);

                if($token_data['status']!=0){

                    $notification = array(
                        'message'=> "Token Data Next Called Successfully",
                        'alert_type'=>'success',
                        'data'=>$token_data['data'],
                        'call_next' =>$call_next,
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    $notification = array(
                        'message'=> "Token Data Does Not Next Called Successfully",
                        'alert_type'=>'warning',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
            }

            return response()->json($notification);
        }
        else if($request->type=='recall'){

            $counter_status = Token::check_counter_status($request);

            if($counter_status==2){

                $notification = array(
                    'message'=> "Counter Was Released. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==3){

                $notification = array(
                    'message'=> "Counter Is Used By Another User. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==1){

                $call_next = Token::recall($request);

                $token_data = Token::load_token($request);

                if($token_data['status']!=0){

                    $notification = array(
                        'message'=> "Token Data Recalled Successfully",
                        'alert_type'=>'success',
                        'data'=>$token_data['data'],
                        'call_next' =>$call_next,
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    $notification = array(
                        'message'=> "Token Data Does Not Recalled Successfully",
                        'alert_type'=>'warning',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
            }

            return response()->json($notification);
        }
        else if($request->type=='served'){

            $counter_status = Token::check_counter_status($request);

            if($counter_status==2){

                $notification = array(
                    'message'=> "Counter Was Released. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==3){

                $notification = array(
                    'message'=> "Counter Is Used By Another User. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==1){

                $call_next = Token::served($request);

                $token_data = Token::load_token($request);

                if($token_data['status']!=0){

                    $notification = array(
                        'message'=> "Token Data Recalled Successfully",
                        'alert_type'=>'success',
                        'data'=>$token_data['data'],
                        'call_next' =>$call_next,
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    $notification = array(
                        'message'=> "Token Data Does Not Recalled Successfully",
                        'alert_type'=>'warning',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
            }

            return response()->json($notification);
        }
        else if($request->type=='noshow'){

            $counter_status = Token::check_counter_status($request);

            if($counter_status==2){

                $notification = array(
                    'message'=> "Counter Was Released. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==3){

                $notification = array(
                    'message'=> "Counter Is Used By Another User. Please Reload Counter Again",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }
            else if($counter_status==1){

                $call_next = Token::noshow($request);

                $token_data = Token::load_token($request);

                if($token_data['status']!=0){

                    $notification = array(
                        'message'=> "Token Data NO Show Successfully",
                        'alert_type'=>'success',
                        'data'=>$token_data['data'],
                        'call_next' =>$call_next,
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    $notification = array(
                        'message'=> "Token Data Does Not NO Show Successfully",
                        'alert_type'=>'warning',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
            }

            return response()->json($notification);
        }
        else if($request->type=='cancel'){

            $cancel_status = Token::cancel_counter($request);

            if($cancel_status==1){

                $status = Common::add_user_activity_history('',0,'Cancel Counter','',18,0);

                if($status!=0){

                    $notification = array(
                        'message'=> "Counter Canceled Successfully",
                        'alert_type'=>'success',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
                else{

                    $notification = array(
                        'message'=> "Counter Does Not Canceled Successfully",
                        'alert_type'=>'warning',
                        'data'=>'',
                        'call_next'=>'',
                        'csrf_token' => csrf_token()
                    );
                }
            }
            else{

                $notification = array(
                    'message'=> "Counter Does Not Canceled Successfully",
                    'alert_type'=>'warning',
                    'data'=>'',
                    'call_next'=>'',
                    'csrf_token' => csrf_token()
                );
            }

            return response()->json($notification);
        }
    }

    public function release_page(){

        $menu_data = Common::get_page_menu();

        $session_data = CommonController::get_session_data();

        $branch_id = $session_data['branch_id'];

        return view('admin.call.release',compact('menu_data','branch_id'));
    }

    public function release_counter(Request $request){

        $release_status = Token::release_counter($request);

        $notification = array();

        if($release_status==1){

            $status = Common::add_user_activity_history('',0,'Release Counter','',17,0);

            if($status!=0){

                $notification = array(
                    'message'=> "Counter Release Successfully",
                    'alert_type'=>'success',
                    'csrf_token' => csrf_token()
                );
            }
            else{

                $notification = array(
                    'message'=> "Counter Does Not Release Successfully",
                    'alert_type'=>'warning',
                    'csrf_token' => csrf_token()
                );
            }
        }
        else{

            $notification = array(
                'message'=> "Counter Does Not Release Successfully",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function my_call_list_grid_page(){

        $menu_data = Common::get_page_menu();
        $grid_right = Common::get_page_menu_grid('call****release_counter');

        return view('admin.call.my_call_list',compact('menu_data','grid_right'));
    }

    public function my_call_list_grid(Request $request){
        
        $grid_data = Token::my_call_list_grid($request);

        echo json_encode($grid_data);
    }
}